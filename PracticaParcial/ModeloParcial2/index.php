<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'clases/vehiculo.php';
require 'clases/servicio.php';

$config ['displayErrorDetails']=true;
$config ['addContentLengthHeader']= false;

$app=new \Slim\App(["settings"=> $config]);

// cargarVehiculo (post): Se deben guardar los siguientes datos: marca, modelo, patente y precio. Los
// datos se guardan en el archivo de texto vehiculos.txt, tomando la patente como identificador(la patente no
// puede estar repetida).
$app->post('/vehiculo',function (Request $request, Response $response){
    $arrayDeParametros = $request->getParsedBody();
    $foto = $_FILES["foto"];
    $objeto = new Vehiculo();
    $objeto->cargarVehiculo($arrayDeParametros, $foto);
    //guardar("vehiculos.txt", $objeto);
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});

// consultarVehiculo (get): Se ingresa marca, modelo o patente, si coincide con algún registro del archivo
// se retorna las ocurrencias, si no coincide se debe retornar “No existe xxx” (xxx es lo que se buscó) La búsqueda
// tiene que ser case insensitive
$app->get('/vehiculo/consulta',function (Request $request, Response $response){
    $arrayDeParametros = $request->getQueryParams();
    //$ArrayDeParametros['key'];
    $objeto = new vehiculo();
    $objeto->consultarVehiculo($arrayDeParametros);
    //buscarValor($ArrayDeParametros["key"],)
    $newResponse='';
    return $newResponse;
});

// cargarTipoServicio(post): Se recibe el nombre del servicio a realizar: id, tipo(de los 10.000km,
// 20.000km, 50.000km), precio y demora, y se guardara en el archivo tiposServicio.txt.
$app->post('/servicio',function (Request $request, Response $response){
    $arrayDeParametros = $request->getParsedBody();
    $objeto = new Servicio();
    $objeto->cargarTipoServicio($arrayDeParametros);
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});

// sacarTurno (get): Se recibe patente y fecha (día) y se debe guardar en el archivo turnos.txt, fecha,
// patente, marca, modelo, precio y tipo de servicio. Si no hay cupo o la materia no existe informar cada caso
// particular.

//VOLVER A VERLO!!!!
$app->get('/turnos/sacar',function (Request $request, Response $response){
    $arrayDeParametros = $request->getQueryParams();
    //$ArrayDeParametros['key'];
    $objeto = new Turno();
    $objeto->sacarTurno($arrayDeParametros);
    //buscarValor($ArrayDeParametros["key"],)
    $newResponse='';
    return $newResponse;
});

//turnos(get): Se devuelve un tabla con todos los servicios.
$app->get('/turnos/consulta',function (Request $request, Response $response){
    $arrayDeParametros = $request->getQueryParams();
    $objeto = new Turno();
    $objeto->turno();
    $newResponse='';
    return $newResponse;
});

// inscripciones(get): Puede recibir el tipo de servicio o la fecha y 
// filtra la tabla de acuerdo al parámetro pasado
$app->get('/turnos/inscripciones',function (Request $request, Response $response){
    $arrayDeParametros = $request->getQueryParams();
    $objeto = new Turno();
    $objeto->inscripciones($arrayDeParametros);
    $newResponse='';
    return $newResponse;
});

// modificarVehiculo(post): Debe poder modificar todos los datos del vehículo menos la patente y se
// debe cargar una imagen, si ya existía una guardar la foto antigua en la carpeta /backUpFotos , el nombre será
// patente y la fecha
$app->post('/vehiculo/modificar',function (Request $request, Response $response){
    $arrayDeParametros = $request->getParsedBody();
    $foto = $_FILES["foto"];
    $objeto = new Vehiculo();
    $objeto->modificarVehiculo($arrayDeParametros, $foto);
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});

/**
 * vehiculos(get): Mostrar una tabla con todos los datos de los vehículos, incluida la foto.
 * Para visualizar las imagenes, abrir la direccion en chrome.
 */
$app->get('/vehiculo/tabla',function (Request $request, Response $response){
    $objeto = new Vehiculo();
    $objeto->vehiculos();
    $newResponse='';
    return $newResponse;
});

$app->run();

?>