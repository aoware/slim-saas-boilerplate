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
                'new_record_url' => 'new_value();'
            ];

        }

        return $this->return_html('configuration_display.html',[
            'definition_id'  => $definition_id,
            'definition'     => $cd_record,
            'values_table'   => $table,
            'type_enums'     => $cd::$enums['type']['enums']
        ]);

    }

    function update($definition_id) {
        
        $post_variables = $this->request->getParsedBody();
        
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_json(false,'Session expired. Please login again.');
        }
                
        $cd = new \models\config_definition();
        $cd->getRecordById($definition_id);
        
        $cd_record   = $cd->recordSet[0];
        $cd->group   = $post_variables['group'];
        $cd->name    = $post_variables['name'];
        $cd->type    = $post_variables['type'];
        $cd->comment = $post_variables['comment'];
        
        $result_update = $cd->updateRecord($definition_id);
        
        if ($result_update !== true) {
            throw new \Exception($result_update);
        }
        
        $data = [
            'redirection' => CONF_base_url . "/backoffice/configurations"
        ];
        
        return $this->return_json(true,'Configuration Definition updated',$data);
        
    }
    
    function delete($definition_id) {
        
        $session = $this->check_login_session();
        
        if ($session['is_logged'] === false) {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        if ($session['user_type'] !== 'agent') {
            return $this->return_json(false,'Session expired. Please login again.');
        }
        
        $cv = new \models\config_value();
        $result_delete = $cv->deleteRecordByConfig_definition_id($definition_id);
        
        if ($result_delete !== true) {
            throw new \Exception($result_delete);
        }

        $cd = new \models\config_definition();
        $result_delete = $cd->deleteRecord($definition_id);
        
        if ($result_delete !== true) {
            throw new \Exception($result_delete);
        }
        
        return $this->return_json(true,'Configuration Definition deleted');
        
    }
    
}