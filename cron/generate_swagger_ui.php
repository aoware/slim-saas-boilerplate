<?php 

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config.php');

$swagger_zip_file   = dirname(__FILE__) . '/swagger_ui.zip';
$swagger_zip_dir    = dirname(__FILE__) . '/swagger_ui/';
$swagger_zip_target = dirname(__FILE__) . '/../public/api-documentation/';

if (file_exists($swagger_zip_file)) {
    unlink($swagger_zip_file);
}

$swagger_zip = file_get_contents("https://github.com/swagger-api/swagger-ui/archive/refs/heads/master.zip");

file_put_contents($swagger_zip_file,$swagger_zip);

$zip = new ZipArchive;

if ($zip->open($swagger_zip_file) === true) {
    $zip->extractTo($swagger_zip_dir);
    $zip->close();
} 
else {
    die('unzip failed');
}

if (file_exists($swagger_zip_target)) {
    $command = "rm -Rf " . $swagger_zip_target;
    $output = shell_exec($command);
}

// Copying files
$fs = new \helpers\file_system;
$fs->copyr($swagger_zip_dir . 'swagger-ui-master/dist/',$swagger_zip_target);

// Adjusting content of all js
$dir_handle = opendir($swagger_zip_target);

while ($file = readdir($dir_handle)) {
    if ($file != "." && $file != "..") {
        
        if (substr($file,0,8) == 'favicon-') {
            unlink($swagger_zip_target . $file);
        }
        
        $file_extension = pathinfo($swagger_zip_target . $file, PATHINFO_EXTENSION);
        if ($file_extension == 'js') {
            $source = file_get_contents($swagger_zip_target . $file);
            $lines  = explode("\n",$source);
            $target = '';
            foreach($lines as $line_number => $line) {
                // Replacing the url of the default swagger file to the one generated
                $pos = strrpos($line,"https://petstore.swagger.io/v2/swagger.json");
                if ($pos !== false) {
                    $line = str_replace("https://petstore.swagger.io/v2/swagger.json","../openapi.json",$line);
                }
                
                // Adding a bit of js to hide the topbar to prevent navigation
                $pos = strrpos($line,"//</editor-fold>");
                if ($pos !== false) {
                    $line  = "  for (const element of document.getElementsByClassName('topbar')){\n";
                    $line .= "     element.style.display = 'none';\n";
                    $line .= "  }";
                }
                
                $target .= $line;
                if ($line_number < (count($lines) - 1)) {
                    $target .= "\n";
                }
            }
            file_put_contents($swagger_zip_target . $file,$target);
        }
        if ($file_extension == 'html') {
            $source = file_get_contents($swagger_zip_target . $file);
            $lines  = explode("\n",$source);
            $target = '';
            foreach($lines as $line_number => $line) {
                $pos = strrpos($line,"rel=\"icon\"");
                if ($pos === false) {
                    $line = str_replace("<title>Swagger UI</title>","<title>API Documentation</title>",$line);
                    $target .= $line;
                    if ($line_number < (count($lines) - 1)) {
                        $target .= "\n";
                    }
                }
            }
            file_put_contents($swagger_zip_target . $file,$target);
        }
    }
}
closedir($dir_handle);

$command = "rm -Rf " . $swagger_zip_dir;
$output = shell_exec($command);

unlink($swagger_zip_file);