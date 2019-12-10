<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    
    require 'vendor/autoload.php';
    require_once "./entidades/vehiculosAPI.php";
    
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;
    
    $app = new \Slim\App(["settings" => $config]);
    
    $app->group('', function()
    {
        $this->post('/cargarVehiculo', \vehiculosAPI::class . ':cargarVehiculo');
        $this->get('/consultarVehiculo', \vehiculosAPI::class . ':consultarVehiculo');
        $this->post('/cargarTipoServicio', \vehiculosAPI::class . ':cargarTipoServicio');
        $this->get('/sacarTurno', \vehiculosAPI::class . ':sacarTurno');
        $this->get('/turnos', \vehiculosAPI::class . ':turnos');
        // $this->delete('/borrarPizza', \PizzeriaAPI::class . ':borrarPizza');
        // $this->get('/logs', \PizzeriaAPI::class . ':consultarLog');
    });
    
    $app->run();
?>