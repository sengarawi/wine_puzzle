<?php
namespace wine;

require_once 'wine/ReadFile.php';

use wine\ReadFile;


//$url = 'https://s3.amazonaws.com/br-user/puzzles/person_wine_3.txt';
$filename = 'data.txt';

$rdObj = new ReadFile();
//$read_file = $rdObj->readFileFromUrl($url, $filename);

$readfile = $rdObj->readFileFromSystem($filename);

print_r($readfile);
echo "file read success";