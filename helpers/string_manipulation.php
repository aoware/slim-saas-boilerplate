<?php

namespace helpers;

class string_manipulation {

    function returnBetweenWords($haystack,$start,$end) {

        $startLength = strlen($start);

        $pos = strpos($haystack,$start);
        if ($pos !== false) {
            $haystack = substr($haystack,$pos+$startLength);
            $pos = strpos($haystack,$end);
            if ($pos !== false) {
                return substr($haystack,0,$pos);
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }

    }

    function returnAfterWord($haystack,$start) {

        $startLength = strlen($start);

        $pos = strpos($haystack,$start);
        if ($pos !== false) {
            return substr($haystack,$pos+$startLength);
        }
        else {
            return null;
        }

    }

    function returnBeforeWord($haystack,$start) {

        $pos = strpos($haystack,$start);
        if ($pos !== false) {
            return substr($haystack,0,$pos);
        }
        else {
            return null;
        }

    }

    function size_to_human_readable($size) {

        if ($size >= 1073741824){
            $size = number_format($size / 1073741824, 2) . ' GB';
        }
        elseif ($size >= 1048576) {
            $size = number_format($size / 1048576, 2) . ' MB';
        }
        elseif ($size >= 1024) {
            $size = number_format($size / 1024, 2) . ' KB';
        }
        elseif ($size > 1) {
            $size = $size . ' bytes';
        }
        elseif ($size = 1) {
            $size = $size . ' byte';
        }
        else {
            $size = 'empty';
        }

        return $size;

    }

    function human_readable_to_size($size) {

        $result_size = 0;

        if ($size != 'empty') {
            $size_parts = explode(' ',$size);

            $size_value = $size_parts[0];
            $unit       = $size_parts[1];

            switch($unit) {
                case 'GB' :
                    $result_size = round($size_value * 1073741824);
                    break;
                case 'MB' :
                    $result_size = round($size_value * 1048576);
                    break;
                case 'KB' :
                    $result_size = round($size_value * 1024);
                    break;
                case 'byte' :
                case 'bytes' :
                    $result_size = $size_value;
                    break;
            }
        }

        return $result_size;

    }

    function accepted_characters($input,$characters) {

        $valid = true;

        $valid_characters = str_split($characters);
        $input_characters = str_split($input);

        foreach($input_characters as $input_character) {
            if (!in_array($input_character,$valid_characters)) {
                $valid = false;
            }
        }

        return $valid;

    }

    function count_characters($input) {

        $result = [];

        $input_characters = str_split($input);

        foreach($input_characters as $input_character) {
            if (isset($result[$input_character])) {
                $result[$input_character]++;
            }
            else {
                $result[$input_character] = 1;
            }
        }

        return $result;

    }
    
    function generateRandomCode($plength) {
        
        if (!is_numeric($plength) || $plength <= 0) {
            $plength = 8;
        }
        if ($plength > 32) {
            $plength = 32;
        }
        
        // Allowed characters
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        
        // Randomizing
        mt_srand(microtime(true) * 1000000);
        
        // Padding
        $pwd = "";
        for ($i = 0; $i < $plength; $i++) {
            $key = mt_rand(0,strlen($chars)-1);
            $pwd .= $chars{$key};
        }
        
        // More randomizing
        for ($i = 0; $i < $plength; $i++) {
            $key1 = mt_rand(0,strlen($pwd)-1);
            $key2 = mt_rand(0,strlen($pwd)-1);
            $tmp = $pwd{$key1};
            $pwd{$key1} = $pwd{$key2};
            $pwd{$key2} = $tmp;
        }
        
        // Even More randomizing!
        $method = rand(0,2);
        switch($method) {
            case 0 :
                // return as is
                break;
            case 1 :
                $pwd = substr(md5($pwd),0,$plength);
                break;
            case 2 :
                $pwd = base64_encode($pwd);
                $pwd = str_replace("+","",$pwd);
                $pwd = str_replace("/","",$pwd);
                $pwd = str_replace("=","",$pwd);
                $pwd = substr($pwd,0,$plength);
                break;
        }
        
        return  $pwd;
    }

    public function slugify($text, string $divider = '-') {

        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        // trim
        $text = trim($text, $divider);
        
        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);
        
        // lowercase
        $text = strtolower($text);
        
        if (empty($text)) {
            return 'n-a';
        }
        
        return $text;
    }
    
}