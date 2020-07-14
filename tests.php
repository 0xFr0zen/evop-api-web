<?php
class Tester {

    private static string $BASE_URL = "https://api.ev-op.de";
    private array $testlinks;

    public function __construct(){
        $this->testlinks = json_decode(file_get_contents(__DIR__.'/tests/links.json'), true);
    }

    private function post_request($url, array $params = array()) {
        $ch = curl_init();
        if ($ch === false) {
            throw new Exception('failed to initialize');
        }
        try {
            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, Tester::$BASE_URL.$url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 2500);

            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        } catch (Exception $e) {
            var_dump($e);
        }
        try {
            $result = curl_exec($ch);
        } catch (Exception $e) {
            echo "??";
            var_dump($e);
        }
        if ($result === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);
        return $result;

    }

    public function run(){
        return array_map(
            function($item) {
                return array($item => $this->post_request($item));
            },
            $this->testlinks
        );
    }

}



$tester = new Tester();
print(json_encode($tester->run(), JSON_NUMERIC_CHECK));