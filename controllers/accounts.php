<?php

namespace controllers;

class accounts extends base_controller {

    function list() {

        $a = new \models\accounts();
        $a->getAllRecords();

        $recordSet = array();
        foreach($a->recordSet as $a_record) {

            $actions = [
                array('label' => 'View/Update' ,'icon' => 'edit'      ,'action' => CONF_base_url . '/backoffice/account/' . $a_record->id . '/update')
            ];

            $au = new \models\account_users();
            $au->getRecordsByAccount_id($a_record->id);

            if (count($au->recordSet) == 0) {
                $actions[] = array('label' => 'Delete'      ,'icon' => 'trash-alt' ,'action' => 'delete_account(' . $a_record->id . ');');
            }

            $record = array(
                'name'         => $a_record->name,
                'slug'         => $a_record->slug,
                'users_count'  => count($au->recordSet),
                'date_created' => $a_record->created,
                'date_updated' => $a_record->modified,
                'actions'      => $actions
            );
            array_push($recordSet,$record);
            unset($au);
        }

        $table = [
            'columns' => [
                ['name' => 'Name`'      ,'sortable' => true],
                ['name' => 'Slug'       ,'sortable' => true],
                ['name' => 'Users Count','sortable' => true],
                ['name' => 'Created'    ,'sortable' => true],
                ['name' => 'Updated'    ,'sortable' => true],
                ['name' => 'Actions'    ,'sortable' => false]
            ],
            'recordset'      => $recordSet,
            'new_record_url' => null
        ];

        return $this->return_html('accounts_list.html',[
            'accounts_table'  => $table
        ]);

    }

    function display($administrator_id = null) {

        if (is_null($administrator_id)) {
            $u_record = null;
        }
        else {
            $u = new \models\users();
            $u->getRecordById($administrator_id);
            $u_record = $u->recordSet[0];
        }

        return $this->return_html('administrator_display.html',[
            'administrator_id'  => $administrator_id,
            'administrator'     => $u_record
        ]);

    }

    function update($administrator_id) {

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
            if ($u->recordSet[0]->id != $administrator_id) {
                return $this->return_json(false,"User already registered");
            }
        }

        $result_read = $u->getRecordById($administrator_id);

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
        $u->login_token        = $u_record->login_token;

        $result_update = $u->updateRecord($administrator_id);

        if ($result_update !== true) {
            throw new \Exception($result_update);
        }

        $data = [
            'redirection' => CONF_base_url . "/backoffice/administrators"
        ];

        return $this->return_json(true,'Administrator updated',$data);

    }

    function delete($account_id) {

        $a = new \models\accounts();
        $result_delete = $a->deleteRecord($account_id);

        if ($result_delete !== true) {
            throw new \Exception($result_delete);
        }

        return $this->return_json(true,'Account deleted');

    }

}