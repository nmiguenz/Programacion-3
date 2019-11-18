<?php
namespace App\Models\ORM;

use App\Models\ORM\usuario;//ruta completa de la clase
use App\Models\AutentificadorJWT;

include_once __DIR__. '../../modelAPI/AutentificadorJWT.php';

class Middleware
{
    public function validarUsuario($request, $response, $next)
    {

        $token = $request->getHeader('token');

        $datos = AutentificadorJWT::ObtenerData($token);   
        
        $usuario = usuario::where('legajo', $datos->legajo)->first();
        if($usuario != null)
        {
            //compruebo que el tipo sea admin
            if(strcasecmp($usuario->tipo, 'admin') == 0)
            {

                try
                {
                    //verifica que el USUARIO sea correcto
                    AutentificadorJWT::VerificarToken($token[0]);
                    $tokenValido = true;
                }
                catch(Exception $e)
                {
                    $newResponse = $response->withJson($e->getMessage(), 200);
                }
                if($tokenValido)
                {
                    $newResponse = $next($request, $response);
                }
            }
            else
            {
                $newResponse = $response->withJson("Su tipo es ".$usuario->tipo.", no puede ingresar", 200);    
            }
        }
        else
        {
            $newResponse = $response->withJson("No existe el usuario con ese legajo", 200);
        }
        
        return $newResponse;
    }
}