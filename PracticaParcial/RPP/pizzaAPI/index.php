<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    
    require 'vendor/autoload.php';
    require_once "./entidades/pizzeriaAPI.php";
    
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;
    
    $app = new \Slim\App(["settings" => $config]);
    
    $app->group('', function()
    {
        $this->post('/pizzas', \PizzeriaAPI::class . ':cargarPizza');
        $this->get('/pizzas', \PizzeriaAPI::class . ':consultarPizza');
        $this->post('/ventas', \PizzeriaAPI::class . ':venderPizza');
        $this->post('/modificarPizza', \PizzeriaAPI::class . ':modificarPizza');
        $this->get('/consultarVentas', \PizzeriaAPI::class . ':consultarVenta');
        $this->delete('/borrarPizza', \PizzeriaAPI::class . ':borrarPizza');
        $this->get('/logs', \PizzeriaAPI::class . ':consultarLog');
    });
    
    $app->run();
?>