<?php
set_time_limit(2);
class Tester {

    private static string $BASE_URL = "http://api.ev-op.de";
    private array $testlinks;

    public function __construct(){
        $this->testlinks = json_decode(file_get_contents(__DIR__.'/tests/links.json'), true);
    }

    private function post_request($url, array $params = array()) {
        $query_content = http_build_query($params);
        $fp = fopen(Tester::$BASE_URL.$url, 'r', FALSE, // do not use_include_path
            stream_context_create([
            'http' => [
            'header'  => [ // header array does not need '\r\n'
                'Content-type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($query_content)
            ],
            'method'  => 'POST',
            'content' => $query_content
            ]
        ]));

        if ($fp === FALSE) {
            return json_encode(['error' => 'Failed to get contents...']);
        }

        $result = stream_get_contents($fp); // no maxlength/offset
        fclose($fp);
        
        return $result;
    }

    public function run(){
        $results = array();
        array_walk(
            $this->testlinks,
            function($item, $index) { 
                $results[$item] = $this->post_request($item);
            }
        );
        var_dump($results);
    }

}


$tester = new Tester();
$tester->run();
