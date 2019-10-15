<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once 'vendor/autoload.php';
require_once 'clases/pizzas.php';

$config ['displayErrorDetails']=true;
$config ['addContentLengthHeader']= false;
$app=new \Slim\App(["settings"=> $config]);

// Ruta: pizzas​ (POST): se ingresa precio, Tipo (“molde” o “piedra”), cantidad(de unidades),sabor
// (muzza;jamón; especial), precio y dos imágenes (guardarlas en la carpeta images/pizzas y cambiarles el nombre
// para que sea único). Se guardan los datos en en el archivo de texto ​Pizza.xxx, ​tomando un id autoincremental
// como identificador, la combinación tipo - sabor debe ser única.
$app->post('/pizzas',function (Request $request, Response $response){
    $arrayDeParametros = $request->getParsedBody();
    $foto = $_FILES["foto"];
    $foto2 = $_FILES["foto2"]; 
    $objeto = new Pizza();
    $objeto->cargarPizza($arrayDeParametros,$foto,$foto2);
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});

// ​Ruta: pizzas​: (GET): Recibe Sabor y Tipo, si coincide con algún registro del archivo ​Pizza.xxx, ​retornar la
// cantidad de producto disponible, de lo contrario informar si no existe el tipo o el sabor. La consulta debe ser ​case
// insensitive
$app->get('/pizzas/consulta',function (Request $request, Response $response){
    $arrayDeParametros = $request->getQueryParams();
    $objeto = new Pizza();
    $objeto->consultarPizza($arrayDeParametros);
    $newResponse='';
    return $newResponse;
});

// 3- (1 pt.) A partir de este punto, se debe guardar en un archivo info.log la información de cada petición recibida
// por la API (ruta, metodo, hora).


// 4-(2 pts.) ​Ruta: ventas ​(POST). Recibe el email del usuario y el sabor,tipo y cantidad ,si el item existe en ​Pizza.xxx,
// y hay stock​ ​guardar en el archivo de texto ​Venta.xxx​ todos los datos , más el precio de la venta, un id
// autoincremental y descontar la cantidad vendida. Si no cumple las condiciones para realizar la venta, informar el
// motivo


$app->run();
?>