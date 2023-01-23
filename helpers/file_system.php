<?php

namespace helpers;

class file_system {

    public function copyr($source, $dest) {

        if (is_dir($source)) {
            $dir_handle = opendir($source);
            
            if (!file_exists($dest)) {
                mkdir($dest);
            }
            
            while ($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    if (is_dir($source . "/" . $file)) {
                        $this->copyr($source . "/" . $file, $dest . "/" . $file);
                    } 
                    else {
                        copy($source . "/" . $file, $dest . "/" . $file);
                    }
                }
            }
            closedir($dir_handle);
        } 
        else {
            copy($source, $dest);
        }
        
    }
    
}