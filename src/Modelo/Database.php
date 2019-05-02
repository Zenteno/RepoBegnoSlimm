<?php
namespace Modelo;

class Database{

	private $database;

	public function __construct($container)
    {
        $this->database = $container->database;
    }


    public function datos(){
    	$arr = $this->database->select('chiquillos', ['id', 'nombre']);
    	return $arr;
    }
}