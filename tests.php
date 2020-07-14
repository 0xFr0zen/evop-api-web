<?php
set_time_limit(2);
header("Content-Type: application/javascript");

class Tester {

    private static string $BASE_URL = "http://api.ev-op.de";
    private array $testlinks;

    public function __construct(){
        $this->testlinks = json_decode(file_get_contents(__DIR__.'/tests/links.json'), true);
    }

    private function post_request($url, array $params = array()) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, Tester::$BASE_URL.$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close ($ch);

        return $result;
    }

    public function run(){
        $results = array();
        array_walk(
            $this->testlinks,
            function($item, $index) {
                // $res = $this->post_request($item);
                array_push($results, array($item => "testing..."));
            }
        );
        return $results;
    }

}


$tester = new Tester();
print(json_encode($tester->run(), JSON_NUMERIC_CHECK));
