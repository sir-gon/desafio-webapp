<?php
chdir(dirname(__DIR__));
$base = dirname(__FILE__);

require_once $base . '/../vendor/autoload.php';
require_once $base . '/config.php';

use GuzzleHttp\Client;

$body = new stdClass();
$body->run = $_POST['run'] ? $_POST['run'] : '';
$body->nombre =  $_POST['nombre'] ? $_POST['nombre'] : '';
$body->fechaNacimiento = $_POST['fechaNacimiento'] ? $_POST['fechaNacimiento'] : '';
$body->sexo = $_POST['sexo'] ? $_POST['sexo'] : '';

$client = new Client([
    'headers' => [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json'
    ]
]);

$response = $client->post($conf['API_URL'],
    ['body' => json_encode($body)]
);

$json = json_decode($response->getBody()->getContents());

echo '<pre>' . var_export($_POST, true) . '</pre>';

echo '<div><b>A&ntilde;os</b>: ' . $json->ageYears  . '</div>';
echo '<div><b>Meses</b>: ' . $json->ageMonths  . '</div>';
echo '<div><b>D&iacute;as</b>: ' . $json->ageDays  . '</div>';
