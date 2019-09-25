<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config ['displayErrorDetails']=true;
$config ['addContentLengthHeader']= false;

$app=new \Slim\App(["settings"=> $config]);

$app->post('/alumno',function (Request $request, Response $response){
    $ArrayDeParametros = $request->getParsedBody();
    $objeto= new stdclass();
    $objeto->nombre=$ArrayDeParametros['nombre'];
    $objeto->apellido=$ArrayDeParametros['apellido'];
    $objeto->email=$ArrayDeParametros['email'];
    $objeto->foto=$ArrayDeParametros['foto'];
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});

$app->run();

?>