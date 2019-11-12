<?php

use App\Models\ORM\usuario;//ruta completa de la clase
use App\Models\AutentificadorJWT;

include_once __DIR__ . '/../../src/app/modelAPI/AutentificadorJWT.php';

class Middleware
{
    public function validarUsuario($request, $response, $next)
    {
        $datos = $request->getParsedBody();
        $email = $datos["email"];
        $clave = $datos["clave"];

        //Busco al usuario por email en la base de datos:
        $usuario = usuario::where('email', $email)->first();

       
        //Compruebo la clave:
        if($usuario != null && hash_equals($usuario->clave, crypt($clave, "aaa")) == true) //generar salt 2do parametro igual al anterior
        {

            //creo el array sin la clave
            $datosUsuario = array(

                'email' => $usuario->email,
                'perfil' => $usuario->perfil
    
            );
    
            //Creo el token
            $token = AutentificadorJWT::CrearToken($datosUsuario);        

            //Reemplazo los datos del Body por el token, sin la clave
            $request = $request->withParsedBody(array('token' => $token));

            $newResponse = $next($request, $response); 
        }
        else
        {
            $newResponse = $response->withJson("No existe $email", 200);
        }

        return $newResponse;
    }
}
    
?>