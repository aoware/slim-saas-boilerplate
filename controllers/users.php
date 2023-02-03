<?php

namespace controllers;

class users extends base_controller {

    function list() {

        $ed = new \helpers\encrypt_decrypt;

        $recordSet = array();

        $query = "select
                    a.id as 'user_id',
                    first_name_crypted,
                    last_name_crypted,
                    email,
                    active,
                    (select COUNT(*) FROM account_users WHERE account_users.user_id = a.id) AS 'accounts_count',
                    a.created,
                    a.modified,
                    last_login,
                    c.id as 'account_id',
                    name
                  from users as a, account_users as b, accounts as c
                  where type = 'client'
                  and a.id = b.user_id
                  and b.account_id = c.id";
        $query_result = $this->dbal->query($query);

        foreach ($query_result as $row) {

            $date_updated = null;
            if (!is_null($row->modified)){
                $date_updated = date('Y-m-d H:i:s',strtotime($row->modified));
            }

            $last_login = null;
            if (!is_null($row->last_login)){
                $last_login = date('Y-m-d H:i:s',strtotime($row->last_login));
            }

            $active = "<input type='checkbox' class='js-switch' data-switchery='true' disabled";
            if ($row->active == 1) {
                $active .= " checked";
            }
            $active .= ">";

            $record = array(
                'account'      => "<a href='" . CONF_base_url . "/backoffice/account/" . $row->account_id . "/update'>" . $row->name . "</a>",
                'email'        => $row->email,
                'first_name'   => $ed->decrypt($row->first_name_crypted),
                'last_name'    => $ed->decrypt($row->last_name_crypted),
                'active'       => $active,
                'accounts'     => $row->accounts_count,
                'date_created' => date('Y-m-d H:i:s',strtotime($row->created)),
                'date_updated' => $date_updated,
                'last_login'   => $last_login,
                'actions' => array(
                    array('label' => 'View/Update' ,'icon' => 'edit'      ,'action' => CONF_base_url . '/backoffice/user/' . $row->user_id . '/update') ,
                    array('label' => 'Delete'      ,'icon' => 'trash-alt' ,'action' => 'delete_user(' . $row->user_id . ');')
                )

            );
            array_push($recordSet,$record);

        }

        $table = [
            'columns' => [
                ['name' => 'Account'       ,'sortable' => true],
                ['name' => 'Email'         ,'sortable' => true],
                ['name' => 'First Name'    ,'sortable' => true],
                ['name' => 'Last Name'     ,'sortable' => true],
                ['name' => 'Active'        ,'sortable' => true],
                ['name' => 'Accounts Count','sortable' => true],
                ['name' => 'Created'       ,'sortable' => true],
                ['name' => 'Updated'       ,'sortable' => true],
                ['name' => 'Last Login'    ,'sortable' => true],
                ['name' => 'Actions'       ,'sortable' => false]
            ],
            'recordset'      => $recordSet,
            'new_record_url' => null
        ];

        return $this->return_html('users_list.html',[
            'users_table'  => $table
        ]);

    }

    function display($user_id = null) {

        if (is_null($user_id)) {
            $u_record = null;
        }
        else {
            $u = new \models\users();
            $u->getRecordById($user_id);
            $u_record = $u->recordSet[0];
        }

        return $this->return_html('user_display.html',[
            'user_id'  => $user_id,
            'user'     => $u_record
        ]);

    }

    function update($user_id) {

        $post_variables = $this->request->getParsedBody();

        $ev = new \helpers\email_validation;
        $result_ev = $ev->validate($post_variables['email']);

        // If email is valid, let's check if already in the DB
        if ($result_ev['validation'] === false) {
            return $this->return_json(false,"Invalid email");
        }

        $u = new \models\users();
        $u->getRecordByEmail($post_variables['email']);
        if (count($u->recordSet) == 1) {
            if ($u->recordSet[0]->id != $user_id) {
                return $this->return_json(false,"User already registered");
            }
        }

        $result_read = $u->getRecordById($user_id);

        if ($result_read !== true) {
            throw new \Exception($result_read);
        }

        $u_record = $u->recordSet[0];
        $u->oauth_provider     = $u_record->oauth_provider;
        $u->oauth_uid          = $u_record->oauth_uid;
        $u->password           = $u_record->password;
        $u->first_name         = $post_variables['first_name'];
        $u->last_name          = $post_variables['last_name'];
        $u->email              = $post_variables['email'];
        $u->location           = $u_record->location;
        $u->picture            = $u_record->picture;
        $u->link               = $u_record->link;
        $u->type               = $u_record->type;
        $u->active             = $u_record->active;
        $u->created            = $u_record->created;
        $u->modified           = date('Y-m-d H:i:s');
        $u->last_login         = $u_record->last_login;
        $u->registration_ip    = $u_record->registration_ip;
        $u->verification_token = $u_record->verification_token;
        $u->verification_date  = $u_record->verification_date;
        $u->verification_ip    = $u_record->verification_ip;
        $u->mfa_token          = $u_record->mfa_token;
        $u->login_token        = $u_record->login_token;

        $result_update = $u->updateRecord($user_id);

        if ($result_update !== true) {
            throw new \Exception($result_update);
        }

        $data = [
            'redirection' => CONF_base_url . "/backoffice/users"
        ];

        return $this->return_json(true,'User updated',$data);

    }

    function delete($user_id) {

        $au = new \models\account_users();
        $result_delete = $au->deleteRecordByUser_id($user_id);

        if ($result_delete !== true) {
            throw new \Exception($result_delete);
        }

        $u = new \models\users();
        $result_delete = $u->deleteRecord($user_id);

        if ($result_delete !== true) {
            throw new \Exception($result_delete);
        }

        return $this->return_json(true,'User deleted');

    }

}