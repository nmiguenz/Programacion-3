<?php
namespace App\Models\ORM;
use App\Models\ORM\materia;
use App\Models\IApiControler;

include_once __DIR__ . '/materia.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\AutentificadorJWT;


class materiaControler implements IApiControler 
{
 	public function Beinvenida($request, $response, $args) {
      $response->getBody()->write("GET => Bienvenido!!! ,a UTN FRA SlimFramework");
    
    return $response;
    }
    
    public function TraerTodos($request, $response, $args) {
       	//return cd::all()->toJson();
        $todosLosCds=materia::all();
        $newResponse = $response->withJson($todosLosCds, 200);  
        return $newResponse;
    }
    public function TraerUno($request, $response, $args) {
     	//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
    	return $newResponse;
    }
    public function CargarUno($request, $response, $args) {
     	 $datos = $request->getParsedBody();
        
        if(isset($datos['nombre'], $datos['cuatrimestre'], $datos['cupos']))
        {
          $materia = new materia;
          if($materia != null)
          {
            $materia->nombre = $datos['nombre'];
            $materia->cuatrimestre = $datos['cuatrimestre'];
            $materia->cupos = $datos['cupos'];
            $materia->save();
            $newResponse = $response->withJson("Se cargo la materia correctamente", 200);
            
          }
          else
          {
            $newResponse = $response->withJson("No se pudo crear la materia", 200);
          }

        }
        else
        {
          $newResponse = $response->withJson("Faltan completar campos del body", 200);  
        }
        return $newResponse;
    }
    
    public function BorrarUno($request, $response, $args) {
  		//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
      	return $newResponse;
    }
     
    public function ModificarUno($request, $response, $args) {
     	//complete el codigo
     	$newResponse = $response->withJson("sin completar", 200);  
		return 	$newResponse;
    }


  
}