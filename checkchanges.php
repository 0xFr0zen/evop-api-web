<?php
$result = array();
if(isset($_REQUEST['ressource'])){
    $page = $_REQUEST['ressource'];
    $content = file_get_contents(__DIR__."/".$page);
    $hash = hash('sha256', $content);

    $result = array("result" => array("hash"=> $hash));
}else {
    $result = array("error" => array("message" => "no page provided"));
}

print(json_encode($result, JSON_NUMERIC_CHECK));
?>