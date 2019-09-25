<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'funciones/parser.php';
require 'clases/vehiculo.php';

$config ['displayErrorDetails']=true;
$config ['addContentLengthHeader']= false;

$app=new \Slim\App(["settings"=> $config]);

$app->post('/vehiculo',function (Request $request, Response $response){
    $ArrayDeParametros = $request->getParsedBody();
    $objeto = new Vehiculo();
    $objeto->marca=$ArrayDeParametros['marca'];
    $objeto->modelo=$ArrayDeParametros['modelo'];
    $objeto->patente=$ArrayDeParametros['patente'];
    $objeto->precio=$ArrayDeParametros['precio'];
    guardar("vehiculos.txt", $objeto);
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});

$app->get('/vehiculo[/]',function (Request $request, Response $response){
    $ArrayDeParametros = $request->getQueryParams();
    //$ArrayDeParametros['key'];
    var_dump($ArrayDeParametros);
    //buscarValor($ArrayDeParametros["key"],)
    return $ArrayDeParametros;
});

$app->run();

?>