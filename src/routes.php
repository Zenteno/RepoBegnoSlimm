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
				$arreglo = ["hola","soy","un","arreglo"];
		$db = new \Modelo\Database($this);
		return $this->view->render($response, 'index.phtml', [
			'arreglo' => $arreglo,
			'otro_arreglo' => $db->datos()
		]);
	});

	//puedes recibir parametros por url, debes ocupar la variable "args"
	$app->get('/saluda/{nombre}', function ($request, $response,$args) {
		return $this->view->render($response, 'index1.phtml', [
			'nombre'=>$args["nombre"]
		]);
	});

	//puedes recibir parametros por url
	$app->get('/tabla', function ($request, $response) {
		$arreglo  = [];
		/*
			éstos items podrías cargarlos después desde una base de datos... o desde un json
		*/

		for($i=0;$i<10;$i++)
			$arreglo[]="Item ".($i+1);
		return $this->view->render($response, 'tabla.html', [
			'items'=>$arreglo
		]);
	});

};
