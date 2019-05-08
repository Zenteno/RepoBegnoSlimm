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

};
