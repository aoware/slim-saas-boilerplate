<?php

// dvgrab --autosplit --timestamp --size 0 --rewind -showstatus tape1-

// ffmpeg -threads 2 -f dv -i tape1-2001.12.25_19-14-29.dv -vf yadif -g 30 -deinterlace -b 900k -acodec mp3 -ab 64k tape1-2001.12.25_19-14-29.mp4

// require_once(dirname(__FILE__) . '/../config.php');
// require_once(dirname(__FILE__) . '/../vendor/autoload.php');

echo chr(27).chr(91).'H'.chr(27).chr(91).'J';

$prefix = readline("Enter Tape Prefix: ");

$command = "dvgrab --autosplit --timestamp --size 0 --rewind -showstatus " . $prefix . "-";

// system($command, $return_value);

// echo "\r\n\r\nResult: " . $return_value . "\r\n";

// Now that  extract has been done, loop through the files and convert to mp4

$dir = dirname(__FILE__);
$files = scandir($dir);

print_r($files);

foreach ($files as $file) {

    if ((substr($file, 0, strlen($prefix)) == $prefix) and (substr($file, -3, 3) == ".dv")) {

        echo "Convert file $file\r\n";

        $mp4_file = str_replace(".dv",".mp4",$file);

        $command = "ffmpeg -threads 2 -f dv -i $file -vf yadif -g 30 -deinterlace -b:v 900k -acodec mp3 -b:a 64k $mp4_file";

        system($command, $return_value);

        echo "\r\n\r\nResult: " . $return_value . "\r\n";

        if ($return_value == 0) {
            unlink($file);
        }

    }

}
