<?php

function dirToArray($dir) {
  
    $result_ = array();
 
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value)
    {
       if (!in_array($value,array(".","..")))
       {
          if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
          {
             $result_[] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
          }
          else
          {
             $result_[] = $dir.DIRECTORY_SEPARATOR.$value;
          }
       }
    }
   
    return $result_;
 } 
$result = array();
$files = dirToArray(__DIR__."/../admin.ev-op.de/");
$files2 = dirToArray(__DIR__);
$hashes = "";
foreach ($files as $fileK => $file) {
    $content = file_get_contents($file);
    $hashes .= hash('sha256', $content);
}



$summeduphash = hash('sha256', $hashes);

$result = array("result" => array("hash"=> $summeduphash));

print(json_encode($result, JSON_NUMERIC_CHECK));

?>