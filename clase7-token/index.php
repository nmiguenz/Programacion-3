<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

require 'vendor/autoload.php';

$config ['displayErrorDetails']=true;
$config ['addContentLengthHeader']= false;  

$app=new \Slim\App(["settings"=> $config]);

$app->group("/auth",function (){
    $this->post("/login", function(Request $request, Response $response){
        $body = $request->getParsedBody();
        
        $key = 'example_key';

        $token = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "user" => $body["user"],
            "pass" => $body["pass"],
        );
        try {
            $jwt = JWT::encode($token, $key);
            $newResponse = $response->withJson($jwt, 200);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
        

        
        return $newResponse;
    });

    $this->get('[/]',function (Request $request, Response $response){
        $key = 'example_key';
        $jwt = $request->getHeader("token")[0];
        $decode = JWT::decode($jwt, $key,array('HS256'));


        $newResponse = $response->withJson($decode, 200);
        return $newResponse;
    });

});

$app->run();
?>