<?php

use Slim\App;
use Slim\Http\Uri;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Slim\Http\Environment;
use Slim\Views\TwigExtension;
use Medoo\Medoo;

return function (App $app) {

	//puedes pasar valores por un arreglo
	$app->get('/', function ($request, $response) {
		return $this->view->render($response, 'index.phtml');
	});

	//puedes recibir parametros por url
	$app->get('/tabla', function ($request, $response) {
		$arreglo  = [];
		for($i=0;$i<10;$i++)
			$arreglo[]="Item ".($i+1);
		return $this->view->render($response, 'tabla.html', [
			'items'=>$arreglo
		]);
	});

	$app->get('/regiones', function ($request, $response) {
		$db = new \Modelo\Database($this);
		return $this->view->render($response, 'regiones.html', [
			'regiones'=>$db->regiones()
		]);
	});
	$app->get('/ciudades', function ($request, $response) {
		$db = new \Modelo\Database($this);
		return $this->view->render($response, 'ciudades.html', [
			'ciudades'=>$db->ciudades()
		]);
	});
	$app->get('/provincias', function ($request, $response) {
		$db = new \Modelo\Database($this);
		return $this->view->render($response, 'provincias.html', [
			'provincias'=>$db->provincias()
		]);
	});

	$app->get('/provincia/{id}/ciudades', function ($request, $response,$args) {
		$db = new \Modelo\Database($this);
		return $this->view->render($response, 'ciudades.html', [
			'ciudades'=> $db->ciudades($args["id"])
		]);
	});

	$app->get('/region/{id}/provincias', function ($request, $response,$args) {
		$db = new \Modelo\Database($this);
		return $this->view->render($response, 'provincias.html', [
			'provincias'=> $db->provincias($args["id"])
		]);
	});

	$app->get('/region/{id}/ciudades', function ($request, $response,$args) {
		$db = new \Modelo\Database($this);
		return $this->view->render($response, 'ciudades.html', [
			'ciudades'=> $db->ciudadesByRegion($args["id"])
		]);
	});

	$app->get('/horoscopo', function ($request, $response,$args) {
		return $this->view->render($response, 'yoli.html');
	});

	$app->get('/busca_patente/{patente}', function ($request, $response, $args) {
		$url = 'https://patenteschile.cl/backend.php';
		$data = array('action' => 'search_by_name', 'name' => $args["patente"]);

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }

		preg_match_all ("/<th>(.*)<\/th>/U", $result, $ths);
		preg_match_all ("/<td>(.*)<\/td>/U", $result, $tds);
		
		$salida = array(
			"Tipo" => strip_tags($tds[0][0]),
			strip_tags($ths[0][0]) => strip_tags($tds[0][1]),
			strip_tags($ths[0][1]) => strip_tags($tds[0][2]),
			"Anio" => strip_tags($tds[0][3]),
			strip_tags($ths[0][3]) => strip_tags($tds[0][4]),
			strip_tags($ths[0][4]) => strip_tags($tds[0][5])
		);
		//var_dump($salida);
		return $response->withJson($salida);
	});

	$app->get('/vehiculo', function ($request, $response, $args) {
		return $this->view->render($response, 'autos.html');
	});
};
