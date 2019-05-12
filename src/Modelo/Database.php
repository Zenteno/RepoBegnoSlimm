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

	public function regiones(){
		$arr = $this->database->select('regiones',['codigo','nombre']);
		return $arr;
	}
	public function provincias($region=null){
		if($region!=null)
			$reg = ["codigo_padre"=>$region];
		else
			$reg = null;
		$arr = $this->database->select('provincias',['codigo','nombre'],$reg);
		return $arr;
	}
	public function ciudades($provincia=null){
		if($provincia!=null)
			$prov = ['codigo_padre'=>$provincia];
		else
			$prov = null;
		$arr = $this->database->select('comunas',['codigo','nombre'],$prov);
		return $arr;
	}

	public function ciudadesByRegion($region){
		$arr = $this->database->select('comunas',
			["[><]provincias" =>["codigo_padre"=>"codigo"]],
			['comunas.codigo','comunas.nombre'],
			[
				"provincias.codigo_padre"=>$region,
				"ORDER"=>["comunas.nombre"=>"ASC"]
			]);
		return $arr;
	}
}