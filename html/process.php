<?php
chdir(dirname(__DIR__));

$base = dirname(__DIR__);


require_once $base . '/vendor/autoload.php';

use GuzzleHttp\Client;

$body = new stdClass();
$body->run = '12345678-9';
$body->nombre = 'Gonzalo';
$body->fechaNacimiento = '1901-02-03';

$client = new Client([
    'headers' => [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ]
]);

$response = $client->post('http://api:8080/persona',
    ['body' => json_encode($body)]
);

echo '<pre>' . var_export($response->getStatusCode(), true) . '</pre>';
echo '<pre>' . var_export($response->getBody()->getContents(), true) . '</pre>';
