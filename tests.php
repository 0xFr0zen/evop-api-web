<?php
class Tester {

    private static string $BASE_URL = "https://api.ev-op.de";
    private array $testlinks;

    public function __construct(){
        $this->testlinks = json_decode(file_get_contents(__DIR__.'/tests/links.json'), true);
    }

    private function hr($url) {
        $opts = array('http' =>
            array(
                'method'  => 'GET'
            )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents(Tester::$BASE_URL.$url, false, $context);
        return json_encode($result, true);
    }

    public function run(){
        return array_map(
            function($item) {
                return array($item => $this->hr($item));
            },
            $this->testlinks
        );
    }

}



$tester = new Tester();
print(json_encode($tester->run(), JSON_NUMERIC_CHECK));