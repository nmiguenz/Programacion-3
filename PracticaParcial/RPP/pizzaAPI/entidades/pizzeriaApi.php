<?php
require_once "./entidades/pizza.php";
require_once "./entidades/archivo.php";
require_once "./entidades/log.php";
require_once "./entidades/venta.php";
class PizzeriaAPI
{
    // se ingresa precio, Tipo (“molde” o “piedra”), cantidad( de unidades),sabor
    // (muzza;jamón; especial), precio y dos imágenes (guardarlas en la carpeta images/pizzas y cambiarles el nombre
    // para que sea único). Se guardan los datos en en el archivo de texto Pizza.xxx, tomando un id autoincremental
    // como identificador, la combinación tipo - sabor debe ser única.
    public static function cargarPizza($request, $response){
        $ruta = "./Pizza.txt";
        $args = $request->getParsedBody();
        $fotos = $request->getUploadedFiles();

        $pizzaRepetida = false;
        $tipoSaborValido = false;

        //Hago validaciones
        //Valido tipo y sabor
        if(Pizza::ValidarTipo($args["tipo"]) == true
            && Pizza::ValidarSabor($args["sabor"]) == true)
        {
            $tipoSaborValido = true;
            $pizzaRepetida = Pizza::ValidarCombinacion($args["tipo"], $args["sabor"]);
        }
      
        //Guardo
        if($tipoSaborValido == true && $pizzaRepetida == false)
        {
            //Creo objeto
            $pizza = new Pizza($args["precio"], $args["tipo"], $args["cantidad"], $args["sabor"], $fotos);
            
            //Guardo
            Pizza::GuardarPizza($pizza);
            
            $listaPizzas = Pizza::TraerPizzas();
            $newResponse = $response->withJson($listaPizzas, 200);
            
            
        }
        else if($pizzaRepetida == true)
        {
            $newResponse = $response->withJson("Pizza repetida", 404);
        }
        else if($tipoSaborValido == false)
        {
            $newResponse = $response->withJson("Tipo o sabor inválido", 404);
        }
        
        PizzeriaAPI::HacerLog("POST", $request);
                  
        return $newResponse;
    }

    // Ruta: pizzas: (GET): Recibe Sabor y Tipo, si coincide con algún registro del archivo Pizza.xxx, retornar la
    // cantidad de producto disponible, de lo contrario informar si no existe el tipo o el sabor. La consulta debe ser case
    // insensitive.    
    public static function consultarPizza($request, $response, $args)
    {
        $tipo = $request->getParam("tipo");
        $sabor = $request->getParam("sabor");
        $listaPizzas = Pizza::TraerPizzas();
        
        $muestroPizza = false;
        $existeDato = false;
        
        $datos = array();
      
        foreach($listaPizzas as $auxPizza)
        {
            if(strcasecmp($auxPizza->tipo, $tipo) == 0 && 
            strcasecmp($auxPizza->sabor, $sabor) == 0)       
            {               
                $existeDato = true;
                //Hago PUSH en el array
                array_push($datos, $auxPizza);
                //Obtengo solo la cantidad de la pizza
                $cantidad = $auxPizza->cantidad;
            }
        }

        if($existeDato == false)
        {
            $datos = "No existe $tipo-$sabor";
        }

        //Devuelvo las  cantidades
        $newResponse = $response->withJson('cantidad: '.$cantidad, 200);

        //Devuelvo todos los datos como json
        //$newResponse = $response->withJson('cantidad: '.$datos, 200);
        //Log
        PizzeriaAPI::HacerLog("GET", $request);
        return $newResponse;
    }

    // ventas (POST). Recibe el email del usuario y el sabor,tipo y cantidad ,si el item existe en Pizza.xxx,
    // y hay stock guardar en el archivo de texto Venta.xxx todos los datos , más el precio de la venta, un id
    // autoincremental y descontar la cantidad vendida. Si no cumple las condiciones para realizar la venta, informar el
    // motivo
    public static function venderPizza($request, $response)
    {
        $ruta = "./Pizza.txt";
        $args = $request->getParsedBody();
        
        $existePizza = false;
        $cantidadSuficiente = false;
        $pizzaVenta;
        $venta;
        
        $mensaje = "Error";
        $listaPizzas = Pizza::TraerPizzas();
        
        for($i=0; $i < sizeof($listaPizzas); $i++)
        {
            $auxPizza = $listaPizzas[$i];
            
            if(strcasecmp($auxPizza->tipo, $args["tipo"]) == 0 && strcasecmp($auxPizza->sabor, $args["sabor"]) == 0)
            {
                $existePizza = true;

                //Verifico la cantidad
                if($auxPizza->cantidad >= $args["cantidad"])
                {
                    $cantidadSuficiente = true;
                    $venta = new Venta($auxPizza->precio,$auxPizza->tipo, $args["cantidad"],
                        $auxPizza->sabor, $args["email"]);
                    Venta::GuardarVenta($venta);

                    //Descuento la cantidad:
                    $auxPizza->cantidad -= $args["cantidad"];
                    Pizza::ModificarPizza($auxPizza);
                }
                break;
            }
        }
        
        if($existePizza == false)
        {
            $mensaje = "No existe la pizza";
        }
        else if($cantidadSuficiente == false)
        {
            $mensaje = "No hay stock";
        }
        else
        {
            $mensaje = Venta::TraerVentas();
        }

        PizzeriaAPI::HacerLog("POST", $request);

        return $newResponse = $response->withJson($auxPizza, 200);
    }

    // Ruta: pizzas (PUT). Se reciben los datos a modificar, incluidas las imágenes. En el caso de haber
    // imágenes, se deben mover las imágenes viejas a la carpeta images/backup

    //Para hacer la modificacion hay que poner todos los campos incluido el id
    public static function ModificarPizza($request, $response, $args)
    {    
        $args = $request->getParsedBody();          
        $fotos = $request->getUploadedFiles();

        $listaPizzas;
        $pizzaRepetida = false;
        $tipoSaborValido = false;
        
        $listaPizzas = Pizza::TraerPizzas();
        if(Pizza::ValidarTipo($args["tipo"]) == true && Pizza::ValidarSabor($args["sabor"]) == true)
        {
            $tipoSaborValido = true;
            
            foreach($listaPizzas as $auxPizza)
            {
                //Combinación tipo-sabor única + id:
                if((strcasecmp($auxPizza->tipo, $args["tipo"]) == 0 && strcasecmp($auxPizza->sabor, $args["sabor"]) == 0)
                        && ($args["id"] != $auxPizza->id))
                {
                    
                    $pizzaRepetida = true;
                    break;
                }
            }
            
        }
        if($tipoSaborValido == true && $pizzaRepetida == false)
        {      
            foreach($listaPizzas as $auxPizza)
            {
                if(strcasecmp($auxPizza->id, $args["id"]) == 0)
                {
                    $pizza = new Pizza($args["precio"], $args["tipo"], $args["cantidad"], $args["sabor"], $fotos);
                    $pizza->id = $auxPizza->id;
            
                    Pizza::ModificarPizzaConFoto($pizza);
                }
            }
        }
        
        $listaPizzas = Pizza::TraerPizzas();
        $newResponse = $response->withJson($listaPizzas, 200);
        PizzeriaAPI::HacerLog("POST", $request);
        return $newResponse;  
    }

    //Ruta: ventas (GET). Debe recibir tipo y/o sabor y mostrar las coincidencias.
    public static function consultarVenta($request, $response, $args)
    {
        
        $existeDato = false;
        $datos = array();

        $tipo = $request->getParam("tipo");
        $sabor = $request->getParam("sabor");

        $listaVentas = Venta::TraerVentas();
      
        foreach($listaVentas as $auxVenta)
        {
            if(strcasecmp($auxVenta->tipo, $tipo) == 0 ||
            strcasecmp($auxVenta->sabor, $sabor) == 0)       
            {               
                $existeDato = true;
                array_push($datos, $auxVenta);
            }
        }
        if($existeDato == false)
        {
            $datos = "No existe $tipo-$sabor";
        }
        $newResponse = $response->withJson($datos, 200);
        PizzeriaAPI::HacerLog("GET", $request);
        return $newResponse;
    }

    // Ruta: pizzas(DELETE), Recibe id de la pizza, de encontrarse en el archivo se deben borrar esos datos y
    // mover la foto a la carpeta /backUpFotos y colocarle al nombre la fecha de hoy.
    public static function BorrarPizza($request, $response, $args)
    {
        $id = $request->getParam("id");

        Pizza::BorrarPizza($id);

        //$newResponse = $response->withJson(Pizza::TraerPizzas(), 200);
        $newResponse = $response->withJson('Se borro la pizza con el id: '.$id, 200);
        
        PizzeriaAPI::HacerLog("DELETE", $request);
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