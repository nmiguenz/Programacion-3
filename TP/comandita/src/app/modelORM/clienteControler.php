<?php
namespace App\Models\ORM;
use Slim\App;
use App\Models\ORM\cliente;
use App\Models\ORM\encuesta;
include_once __DIR__ . '/cliente.php';
include_once __DIR__ . '/encuesta.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class clienteControler
{
    public function EncuestaCliente($request, $response, $args)
    {
        $datos = $request->getParsedBody();

        if(isset($datos["puntajeMesa"], $datos["puntajeRestaurante"], $datos["puntajeMozo"],
            $datos["puntajeCocinero"], $datos["textoExperiencia"], $datos["codigoMesa"]))
        {
            if($datos["puntajeMesa"]>= 1 && $datos["puntajeMesa"]<= 10)
            {
                if($datos["puntajeRestaurante"]>= 1 && $datos["puntajeRestaurante"]<= 10)
                {
                    if($datos["puntajeMozo"]>= 1 && $datos["puntajeMozo"]<= 10)
                    {
                        if($datos["puntajeCocinero"]>= 1 && $datos["puntajeCocinero"]<= 10)
                        {
                            $encuesta = new encuesta();
                            $encuesta->puntaje_mesa = $datos["puntajeMesa"];
                            $encuesta->puntaje_restaurante = $datos["puntajeRestaurante"];
                            $encuesta->puntaje_mozo = $datos["puntajeMozo"];
                            $encuesta->puntaje_cocinero = $datos["puntajeCocinero"];
                            $encuesta->texto_experiencia = $datos["textoExperiencia"];
                            
                            $cliente = cliente::where('codigo_mesa', $datos['codigoMesa'])->first();
                            $encuesta->id_cliente = $cliente->id;
                            $encuesta->save();

                            $cliente->id_encuesta = $encuesta->id;
                            $cliente->save();
                            $newResponse = $response->withJson("Muchas gracias por completar la encuesta", 200);
                        }
                        else
                        {
                            $newResponse = $response->withJson("El puntaje tiene que ser del 1 al 10", 200);
                        }
                    }
                    else
                    {
                        $newResponse = $response->withJson("El puntaje tiene que ser del 1 al 10", 200);
                    }
                }
                else
                {
                    $newResponse = $response->withJson("El puntaje tiene que ser del 1 al 10", 200);
                }
            }
            else
            {
                $newResponse = $response->withJson("El puntaje tiene que ser del 1 al 10", 200);
            }
        }
        else
        {
            $newResponse = $response->withJson("Faltan ingresar datos para completar la encuesta", 200);
        }
        return $newResponse;
    }
}