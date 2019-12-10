<?php
require_once "./entidades/vehiculo.php";
require_once "./entidades/archivo.php";
require_once "./entidades/log.php";
require_once "./entidades/servicio.php";
require_once "./entidades/turno.php";

class VehiculosAPI
{
    // caso: cargarVehiculo (post): Se deben guardar los siguientes datos: marca, patente y kms. Los datos se
    // guardan en el archivo de texto vehiculos.xxx, tomando la patente como identificador(la patente no puede estar
    // repetida).
    public static function cargarVehiculo($request, $response){
        $ruta = "./vehiculos.txt";
        $args = $request->getParsedBody();
        $fotos = $request->getUploadedFiles();

        $vehiculoRepetido = false;
        $patenteValida = false;

        //Hago validaciones
        //Valido tipo y sabor
        if(Vehiculo::ValidarPatente($args["patente"]) == true)
        {
            $patenteValida = true;
        }
      
        //Guardo
        if($patenteValida == true)
        {
            //Creo objeto
            $vehiculo = new Vehiculo($args["patente"], $args["marca"], $args["kms"], $fotos);
            
            //Guardo
            Vehiculo::GuardarVehiculo($vehiculo);
            
            $listaVehiculos = Vehiculo::TraerVehiculos();
            $newResponse = $response->withJson($listaVehiculos, 200);
            
            
        }
        else
        {
            $newResponse = $response->withJson("No se guardo porque la patente esta repetida", 404);
        }
        
        VehiculosAPI::HacerLog("POST", $request);
                  
        return $newResponse;
    }

    // caso: consultarVehiculo (get): Se ingresa marca o patente, si coincide con algún registro del archivo se
    // retorna las ocurrencias, si no coincide se debe retornar “No existe xxx” (xxx es lo que se buscó) La búsqueda
    // tiene que ser case insensitive.
    public static function consultarVehiculo($request, $response, $args){
        $patente = strtolower($request->getParam("patente"));
        $marca = strtolower($request->getParam("marca"));
        $listaVehiculos = Vehiculo::TraerVehiculos();
        
        $existeDato = false;
        
        $datos = array();
      
        foreach($listaVehiculos as $auxVehiculos)
        {
            if(strcasecmp(strtolower($auxVehiculos->patente), $patente) == 0 || 
            strcasecmp(strtolower($auxVehiculos->marca), $marca) == 0)       
            {               
                $existeDato = true;
                //Hago PUSH en el array
                array_push($datos, $auxVehiculos);
                
            }
        }

        if($existeDato == false)
        {
            $datos = "No existe $patente-$marca";
        }

        //Devuelvo todos los datos como json
        $newResponse = $response->withJson($datos, 200);
        //Log
        VehiculosAPI::HacerLog("GET", $request);
        return $newResponse;
    }

    // caso: cargarTipoServicio(post): Se recibe el nombre del servicio a realizar: id, tipo(de los 10.000km,
    // 20.000km, 50.000km), precio y demora, y se guardara en el archivo tiposServicio.xxx.
    public static function cargarTipoServicio($request, $response){
        $ruta = "./tipoServicios.txt";
        $args = $request->getParsedBody();

        $servicioValido = false;

        //Hago validaciones
        //Valido tipo y sabor
        if(Servicio::ValidarTipo($args['tipo']) == true)
        {
            if(Servicio::ValidarId($args['id']) == true)
            {
                $servicio = new Servicio($args['id'],$args['tipo'],$args['precio'],$args['demora']);
                if(Servicio::GuardarServicio($servicio))
                {
                    $listaServicios = Servicio::TraerServicios();
                    $newResponse = $response->withJson($listaServicios, 200);
                }
            }
            else
            {
                $newResponse = $response->withJson("El ID es invalido o esta repetido", 404);
            }
        }
        else
        {
            $newResponse = $response->withJson("El tipo es invalido", 404);
        }
        
        VehiculosAPI::HacerLog("POST", $request);
                  
        return $newResponse;
    }

    // caso: sacarTurno (get): Se recibe patente, precio y fecha (día) y se debe guardar en el archivo
    // turnos.txt, fecha, patente, modelo, precio y tipo de servicio. Si no hay cupo o la materia no existe informar cada
    // caso particular.
    public static function sacarTurno($request, $response, $args){
        
        $patente = strtolower($request->getParam("patente"));
        $precio = strtolower($request->getParam("precio"));
        $fecha = strtolower($request->getParam("fecha"));

        //Se fija que la patente este repetida
        if(!(Vehiculo::ValidarPatente($patente)))
        {
            $listaServicios = Servicio::TraerServicios();
            if(is_array($listaServicios))
            {
                foreach($listaServicios as $auxServicio)
                {
                    //Se queda con el precio que coincide con el auxiliar
                    if($auxServicio->precio == $precio)
                    {
                        break;
                    }
                }
            }
            // //servicio random
            // $idServicio = rand(0, (sizeof($listaServicios) - 1) );
        
            $vehiculo = Vehiculo::TraerUnVehiculo($patente);
            $marca = $vehiculo->marca;
            if($listaServicios != null && $vehiculo != null)
            {
                
                $turno = new Turno($fecha, $patente, $marca,
                    $auxServicio->precio, $auxServicio->tipo);

                Turno::GuardarTurno($turno);
            }
            
            $listaTurnos = Turno::TraerTurnos();
            $newResponse = $response->withJson($listaTurnos, 200);
                                            
        }
        else
        {
            $newResponse = $response->withJson('La patente no existe', 404);
        }    
        
        return $newResponse;
    }

    //caso: turnos(get): Se devuelve un tabla con todos los servicios
    public static function turnos($request, $response, $args){
        
        $listaTurnos = Turno::TraerTurnos();
        $tablaTurnos = Turno::CrearTabla($listaTurnos);
    
        echo $tablaTurnos;

        $newResponse = $response->withJson('tabla creada', 200);
        return $newResponse;

    }

    //logs (GET). Recibe una fecha y muestra los logs posteriores a esta.
    public static function consultarLog($request, $response, $args)
    {
        $fecha = $request->getParam("fecha");

        $fecha = new DateTime($fecha, new DateTimeZone('America/Argentina/Buenos_Aires'));
        $fechaLog;
        $datos = array();
        $listaLogs = Log::TraerLogs();
        
        foreach($listaLogs as $log)
        {
            $fechaLog = new DateTime($log->hora, new DateTimeZone('America/Argentina/Buenos_Aires'));
            
            //Filtra los horarios posteriores a la fecha pasada como parametro
            if($fechaLog > $fecha)
            {             
                array_push($datos, $log);
            }
        }

        $newResponse = $response->withJson($datos, 200);

        PizzeriaAPI::HacerLog("GET", $request);
        return $newResponse;
    } 

    public static function HacerLog($caso, $request)
    {
        $uri = $request->getUri();
        $log = new Log($caso, (string)$uri);
        Log::GuardarLog($log);
    }
}
?>