<?php
chdir(dirname(__DIR__));
$base = dirname(__FILE__);

require_once $base . '/../vendor/autoload.php';
require_once $base . '/config.php';

use GuzzleHttp\Client;

/**
* QUERY API
*/
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

$json = $response->getBody()->getContents();
$jsonDecoded = json_decode($json);

echo '<div><b>A&ntilde;os</b>: ' . $jsonDecoded->ageYears  . '</div>';
echo '<div><b>Meses</b>: ' . $jsonDecoded->ageMonths  . '</div>';
echo '<div><b>D&iacute;as</b>: ' . $jsonDecoded->ageDays  . '</div>';

/**
* Intentar crear BD
*/

echo '<div>* Almacenando en Base de Datos</div>';

$client = new Client([]);

$response = $client->put($conf['DB_URL'] . '/' . $conf['DB_NAME'], [
    'auth' => [
        $conf['DB_USER'],
        $conf['DB_PASS']
    ],
    'http_errors' => false
  ]
);

if($response->getStatusCode() === 200)
{
  echo '<div>* Base de datos creada</div>';
}

if($response->getStatusCode() === 412)
{
  echo '<div>* Base de datos existente</div>';
}


/**
* Intentar guardar dato en BD
*/

$client = new Client([]);
$url = $conf['DB_URL'] . '/' . $conf['DB_NAME'] . '/' . $jsonDecoded->run;
$response = $client->put($url, [
    'auth' => [
        $conf['DB_USER'],
        $conf['DB_PASS']
    ],
    'http_errors' => false,
    'json' => $jsonDecoded
  ]
);


if($response->getStatusCode() === 201)
{
  echo '<div>* Documento guardado en base de datos</div>';
} else {

  echo '<div>Documento enviado: <pre>' . $json . ' </pre></div>';
  echo '<div>Codigo de estado de respuesta:<pre> ' . $response->getStatusCode()  . ' </pre></div>';
  echo '<div>Contenido de estado de respuesta:<pre> ' . $response->getBody()  . ' </pre></div>';

}
