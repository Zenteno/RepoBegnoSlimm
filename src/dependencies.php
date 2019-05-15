<?php

use Slim\App;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Http\Environment;
use Slim\Views\TwigExtension;
use Medoo\Medoo;


return function (App $app) {
	$container = $app->getContainer();

	/*
	// view renderer
	$container['renderer'] = function ($c) {
		$settings = $c->get('settings')['renderer'];
		return new \Slim\Views\PhpRenderer($settings['template_path']);
	};
	*/

	// Registrar twig al contenedor de slimm
	$container['view'] = function ($c) {
		$view = new Twig('../templates');
	
		// Instancia y añade la extensión
		$router = $c->get('router');
		$uri = Uri::createFromEnvironment(new Environment($_SERVER));
		$view->addExtension(new TwigExtension($router, $uri));

		return $view;
	};
	
	// Registra la base de datos
	$container['database'] = function () {
		return new Medoo([
			'database_type' => 'mysql',
			'database_name' => 'shile',
			'server' => 'localhost:3306',
			'username' => 'homestead',
			'password' => 'secret',
			'charset' => 'utf8'
		]); 
	};

	// monolog
	$container['logger'] = function ($c) {
		$settings = $c->get('settings')['logger'];
		$logger = new \Monolog\Logger($settings['name']);
		$logger->pushProcessor(new \Monolog\Processor\UidProcessor());
		$logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
		return $logger;
	};
};
