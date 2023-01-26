<?php

namespace helpers;

class email_validation {

    function validate($email) {

        $result_validation = true;
        $result_message    = 'Success';

        $emailReg    = "/^([\w-\.]+@([\w-]+\.)+[\w-]{2,14})?$/";

        // Email validation
        if (strlen($email) == 0) {
            $result_validation = false;
            $result_message    = 'empty email address';
        }
        else {
            $arr = explode('@',$email,2);
            if (!checkdnsrr($arr[1],'MX')) {
                $result_validation = false;
                $result_message    = 'invalid domain ' . $arr[1];
            }
            else {
                $bac = new \apis\bac(CONF_bac_key,CONF_bac_password);
                $result_bac = $bac->validate_email($email);
                // The email is valid, but it is a disposable email
                if ($result_bac['resultCode'] == '01') {
                    if ($result_bac['emailProperties']['is_disposable'] == true) {
                        $result_validation = false;
                        $result_message    = 'disposable domain ' . $arr[1];;
                    }
                }                    
                // The email is invalid
                if ($result_bac['resultCode'] == '02') {
                    $result_validation = false;
                    $result_message    = 'Success';
                }

            }
        }

        return [
            'validation' => $result_validation,
            'message'    => $result_message
        ];

    }

}