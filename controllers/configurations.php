<?php

namespace controllers;

class configurations extends base_controller {

    function list() {

        $session = $this->check_login_session();

        if ($session['is_logged'] === false) {
            return $this->return_redirection(CONF_base_url);
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_redirection(CONF_base_url);
        }

        $cd = new \models\config_definition;
        $cd->getAllRecords();

        $recordSet = array();
        foreach($cd->recordSet as $cd_record) {

            $cv = new \models\config_value;
            $cv->getRecordsByConfig_definition_id($cd_record->id);

            $values = "";
            foreach($cv->recordSet as $cv_record) {
                if ($cd_record->type == 'array') {
                    $values .= $cv_record->profile . ":&nbsp;[" . $cv_record->key . "]&nbsp;" . $cv_record->value . "<br />";
                }
                else {
                    $values .= $cv_record->profile . ":&nbsp;" . $cv_record->value . "<br />";
                }
            }

            unset($cv);

            $record = array(
                'id'      => $cd_record->id,
                'group'   => $cd_record->group,
                'name'    => $cd_record->name,
                'type'    => $cd_record->type,
                'comment' => $cd_record->comment,
                'values'  => $values,
                'actions' => array(
                    array('label' => 'View/Update' ,'icon' => 'edit'      ,'action' => CONF_base_url . '/backoffice/configuration/' . $cd_record->id . '/update') ,
                    array('label' => 'Delete'      ,'icon' => 'trash-alt' ,'action' => 'delete_configuration(' . $cd_record->id . ');')
                )
            );
            array_push($recordSet,$record);

        }

        $table = [
            'columns' => [
                ['name' => 'Id'     ,'sortable' => true],
                ['name' => 'Group'  ,'sortable' => true],
                ['name' => 'Name'   ,'sortable' => true],
                ['name' => 'Type'   ,'sortable' => true],
                ['name' => 'Comment','sortable' => true],
                ['name' => 'Values' ,'sortable' => false],
                ['name' => 'Actions','sortable' => false]
              ],
            'recordset'      => $recordSet,
            'new_record_url' => '/backoffice/configuration/new'
        ];

        return $this->return_html('configurations_list.html',[
            'configurations_table'  => $table
        ]);

    }

    function display($definition_id = null) {

        $session = $this->check_login_session();

        if ($session['is_logged'] === false) {
            return $this->return_redirection(CONF_base_url);
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_redirection(CONF_base_url);
        }

        if (is_null($definition_id)) {
            $cd_record = null;
            $table     = null;
        }
        else {
            $cd = new \models\config_definition;
            $cd->getRecordById($definition_id);
            $cd_record = $cd->recordSet[0];

            $cv = new \models\config_value;
            $cv->getRecordsByConfig_definition_id($definition_id);

            $recordSet = array();
            foreach($cv->recordSet as $cv_record) {

                $record = array(
                    'id'      => $cv_record->id,
                    'profile' => $cv_record->profile,
                    'key'     => $cv_record->key,
                    'value'   => $cv_record->value,
                    'actions' => array(
                        array('label' => 'View/Update' ,'icon' => 'edit'      ,'action' => CONF_base_url . '/backoffice/configuration/' . $cd_record->id . '/update') ,
                        array('label' => 'Delete'      ,'icon' => 'trash-alt' ,'action' => 'delete_configuration(' . $cd_record->id . ');')
                    )
                );
                array_push($recordSet,$record);
            }

            $table = [
                'columns' => [
                    ['name' => 'Id'     ,'sortable' => true],
                    ['name' => 'Profile','sortable' => true],
                    ['name' => 'Key'    ,'sortable' => true],
                    ['name' => 'Value'  ,'sortable' => false],
                    ['name' => 'Actions','sortable' => false]
                ],
                'recordset'      => $recordSet,
                'new_record_url' => 'new_value()'
            ];

        }

        return $this->return_html('configuration_display.html',[
            'definition_id'  => $definition_id,
            'definition'     => $cd_record,
            'values_table'   => $table,
            'type_enums'     => $cd::$enums['type']['enums']
        ]);

    }

    function details($id) {

        $login = $this->check_login();
        if ($login === false) {
            $result = [
                'type'      => 'redirection',
                'path'      => CONF_base_url . "/login",
                'http_code' => 307
            ];
        }
        else {

            $cd = new \models\config_definition;
            $cd->getRecordById($id);

            $cv = new \models\config_value;
            $cv->getAllRecords();

            $recordSet = array();
            foreach($cd->recordSet as $cd_record) {

                $record = array(
                    'id'      => $cd_record->id,
                    'group'   => $cd_record->group,
                    'name'    => $cd_record->name,
                    'type'    => $cd_record->type,
                    'comment' => $cd_record->comment,
                    'actions' => array(
                        array('label' => 'View/Update' ,'icon' => 'edit'      ,'action' => CONF_base_url . '/configurations/update/' . $cd_record->id) ,
                        array('label' => 'Delete'      ,'icon' => 'trash-alt' ,'action' => CONF_base_url . '/configurations/delete/' . $cd_record->id)
                    )

                );
                array_push($recordSet,$record);

            }

            $table = [
                'columns' => [
                    ['name' => 'Id'     ,'sortable' => true],
                    ['name' => 'Group'  ,'sortable' => true],
                    ['name' => 'Name'   ,'sortable' => true],
                    ['name' => 'Type'   ,'sortable' => true],
                    ['name' => 'Comment','sortable' => true],
                    ['name' => 'Actions','sortable' => false]
                ],
                'recordset'      => $recordSet,
                'new_record_url' => '/configurations/new'
            ];

            $result = [
                'type' => 'html',
                'response' => $this->app->view->render($this->response, 'configurations_details.html',
                    array_merge($this->app->template_options,
                        [
                            'config'             => $cd->recordSet[0],
                            'config_value_table' => $table
                        ])
                    )
            ];
        }

        return $result;
    }

    function update($id) {

        $login = $this->check_login();
        if ($login === false) {
            $result = [
                'type'      => 'redirection',
                'path'      => CONF_base_url . "/login",
                'http_code' => 307
            ];
        }
        else {

            $allPostPutVars = $this->request->getParsedBody();

            $u = new \models\users;
            $u->getRecordById($id);

            $u_record = $u->recordSet[0];

            $u->oauth_provider     = $u_record->oauth_provider;
            $u->oauth_uid          = $allPostPutVars['email'];
            if ($allPostPutVars['password'] != '') {
                $u->password       = md5($allPostPutVars['password']);
            }
            else {
                $u->password       = $u_record->password;
            }
            $u->first_name         = $allPostPutVars['first_name'];
            $u->last_name          = $allPostPutVars['last_name'];
            $u->username           = $allPostPutVars['email'];
            $u->email              = $allPostPutVars['email'];
            $u->location           = $u_record->location;
            $u->picture            = $u_record->picture;
            $u->link               = $u_record->link;
            $u->created            = $u_record->created;
            $u->modified           = date('Y-m-d H:i:s');
            $u->last_login         = $u_record->last_login;
            $u->login_token        = $u_record->login_token;

            $update = $u->updateRecord($id);

            if ($update === true) {
                $result = [
                    'type'      => 'redirection',
                    'path'      => CONF_base_url . "/users",
                    'http_code' => 303
                ];
            }
            else {
                $result = [
                    'type' => 'html',
                    'response' => $this->app->view->render($this->response, 'users_details.html',
                        array_merge($this->app->template_options,
                            [
                                'user'          => $u->recordSet[0],
                                'error_message' => $update
                            ])
                        )
                ];
            }
        }

        return $result;

    }

    function insert() {

        $login = $this->check_login();
        if ($login === false) {
            $result = [
                'type'      => 'redirection',
                'path'      => CONF_base_url . "/login",
                'http_code' => 307
            ];
        }
        else {

            $allPostPutVars = $this->request->getParsedBody();

            $u = new \models\users;

            $u->oauth_provider     = 'email';
            $u->oauth_uid          = $allPostPutVars['email'];
            $u->password           = md5($allPostPutVars['password']);
            $u->first_name         = $allPostPutVars['first_name'];
            $u->last_name          = $allPostPutVars['last_name'];
            $u->username           = $allPostPutVars['email'];
            $u->email              = $allPostPutVars['email'];
            $u->location           = '';
            $u->picture            = '';
            $u->link               = '';
            $u->created            = date('Y-m-d H:i:s');
            $u->modified           = null;
            $u->last_login         = null;
            $u->login_token        = null;

            $result_insert = $u->saveRecord();

            if ($result_insert === true) {
                $result = [
                    'type'      => 'redirection',
                    'path'      => CONF_base_url . "/users",
                    'http_code' => 303
                ];
            }
            else {
                $result = [
                    'type' => 'html',
                    'response' => $this->app->view->render($this->response, 'users_details.html',
                        array_merge($this->app->template_options,
                            [
                                'user'          => $u->recordSet[0],
                                'error_message' => $result_insert
                            ])
                        )
                ];
            }
        }

        return $result;

    }

    function delete($id) {

        $login = $this->check_login();
        if ($login === false) {
            $result = [
                'type'      => 'redirection',
                'path'      => CONF_base_url . "/login",
                'http_code' => 307
            ];
        }
        else {

            $u = new \models\users;
            $result_delete = $u->deleteRecord($id);

            if ($result_delete === true) {
                $result = [
                    'type'      => 'redirection',
                    'path'      => CONF_base_url . "/users",
                    'http_code' => 303
                ];
            }
            else {
                $result = [
                    'type' => 'html',
                    'response' => $this->app->view->render($this->response, 'users_details.html',
                        array_merge($this->app->template_options,
                            [
                                'review'        => $u->recordSet[0],
                                'error_message' => $result_delete
                            ])
                        )
                ];
            }
        }

        return $result;

    }

}