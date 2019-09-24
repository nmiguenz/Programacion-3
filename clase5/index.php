<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$config ['displayErrorDetails']=true;
$config ['addContentLengthHeader']= false;

$app=new \Slim\App(["settings"=> $config]);

/*
$app->get('[/]',function (Request $request, Response $response){
    $response->getBody()->write("GET => Bienvenido!!");
    return $response;
}); //trae recursos   

$app->post('[/]',function (Request $request, Response $response){
    $response->getBody()->write("Post => Bienvenido!!");
    return $response;
});//Post:Cargar recursos
$app->put('[/]',function (Request $request, Response $response){
    $response->getBody()->write("Put => Bienvenido!!");
    return $response;
});//Put:modificar recursos
$app->delete('[/]',function (Request $request, Response $response){
    $response->getBody()->write("Delete => Bienvenido!!");
    return $response;
});// Delete: borrar recursos
*/

/**
 * Retorna objetos
 */
$app->get('/datos/',function (Request $request, Response $response){
    $datos= array('nombre'=> ' rogelio', ' apellido'=> 'agua', 'edad'=> 40);
    $newResponse= $response->withJson($datos,200);
    return $newResponse;
});

/**
 * Recibe objetos
 */
$app->post('/datos/',function (Request $request, Response $response){
    $ArrayDeParametros = $request->getParsedBody();
    $objeto= new stdclass();
    $objeto->nombre=$ArrayDeParametros['nombre'];
    $objeto->apellido=$ArrayDeParametros['apellido'];
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});

/**
 * Recibir objetos y archivos
 */
// $app->post('/datos/',function (Request $request, Response $response){
//     $ArrayDeParametros = $request->getParsedBody();
//     $objeto= new stdclass();
//     $objeto->nombre=$ArrayDeParametros['nombre'];
//     $objeto->apellido=$ArrayDeParametros['apellido'];
    
//     $archivos=$request ->getUploadedFiles();
//     $destino="./fotos/";
//     $nombreAnterior=$archivos['foto']->getClientFilename();
//     $extension=explode(".",$nombreAnterior);
//     $extension=array_reverse($extension);
//     $archivos['fotos']->moveTo($destino.$objeto->nombre.".".$extension[0]);
//     $response->getBody()->write("cd");
//     return $response;
// });

$app->run();

?>