<?php
use App\Models\ORM\usuario;//ruta completa de la clase
use App\Models\AutentificadorJWT;

include_once __DIR__ . '/../../src/app/modelAPI/AutentificadorJWT.php';

class Middleware
{
    public function validarUsuario($request, $response, $next)
    {
        $datos = $request->getParsedBody();
        $clave = $datos["clave"];
        $legajo = $datos['legajo'];
        
        $usuario = usuario::where('legajo', $legajo)->first();
       
       
        //Compruebo la clave:
        if($usuario != null && hash_equals($usuario->clave, crypt($clave, "aaa")) == true)
        {
            
            //creo el array sin la clave
            $datosUsuario = array(
                'legajo' => $usuario->legajo,
                'email' => $usuario->email,
                'tipo' => $usuario->tipo

            );
    
            //Creo el token
            $token = AutentificadorJWT::CrearToken($datosUsuario);        
            //Reemplazo los datos del Body por el token, sin la clave
            $request = $request->withParsedBody(array('token' => $token));
            $newResponse = $next($request, $response); 
        }
        else
        {
            $newResponse = $response->withJson("No existe el legajo $legajo", 200);
        }
        return $newResponse;
    }
}
    
?>