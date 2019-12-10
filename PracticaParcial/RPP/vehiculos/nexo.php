<?php
require_once "./entidades/archivo.php";
require_once "./entidades/vehiculo.php";
require_once "./entidades/servicio.php";
require_once "./entidades/turno.php";

//POST
if(isset($_POST['caso']))
{
    $caso = $_POST['caso'];

    switch($caso)
    {   
        // Se deben guardar los siguientes datos: marca, patente y kms. Los datos se
        // guardan en el archivo de texto vehiculos.xxx, tomando la patente como identificador(la patente no puede estar
        // repetida).
        case 'cargarVehiculo':
            if(isset($_POST["marca"], $_POST["patente"], $_POST["kms"], $_FILES["archivo"]))
            {
                if(Vehiculo::ValidarPatente($_POST["patente"]))
                {
                    $archivo = $_FILES["archivo"];

                    $rutaFoto = Archivo::GuardarArchivoTemporal($archivo, "./img/");
                    
                    $vehiculo = new Vehiculo($_POST["marca"], $_POST["patente"], $_POST["kms"], $rutaFoto);

                    //Guardo
                    if(Vehiculo::GuardarVehiculo($vehiculo))
                    {
                        $listaVehiculos = Vehiculo::TraerVehiculos();
                        
                        $newResponse = json_encode($listaVehiculos);
                    }
                    
                }
                else
                {
                    $newResponse = json_encode("Vehiculo repetido");
                }
                
            }
            echo $newResponse;
            
            break;

        // Se recibe el nombre del servicio a realizar: id, tipo(de los 10.000km,
        // 20.000km, 50.000km), precio y demora, y se guardara en el archivo tiposServicio.xxx
        case 'cargarTipoServicio':
            if(isset($_POST["id"], $_POST["tipo"], $_POST["precio"], $_POST["demora"]))
            {
                if(Servicio::ValidarTipo($_POST["tipo"]))
                {
                    if(Servicio::ValidarId($_POST["id"]))
                    {
                        $servicio = new Servicio($_POST["id"], $_POST["tipo"], $_POST["precio"], $_POST["demora"]);
           
                        if(Servicio::GuardarServicio($servicio))
                        {
                            $listaServicios = Servicio::TraerServicios();
                            
                            $newResponse = json_encode($listaServicios);
                        }
                    }
                    else
                    {
                        $newResponse = json_encode("id invalido");
                    }
                }
                else
                {
                    $newResponse = json_encode("Tipo invalido");
                }
                
            }
            echo $newResponse;
            break;
        
        // caso: modificarVehiculo(post): Debe poder modificar todos los datos del vehículo menos la patente y
        // se debe cargar una imagen, si ya existía una guardar la foto antigua en la carpeta /backUpFotos , el nombre será
        // patente y la fecha.
        case 'modificarVehiculo':
            if(isset($_POST["marca"], $_POST["patente"], $_POST["kms"], $_FILES["archivo"]))
                    {     
                        if(!(Vehiculo::ValidarPatente($_POST["patente"])))
                        {
                            $archivo = $_FILES["archivo"];
                            $rutaFoto = Archivo::GuardarArchivoTemporal($archivo, "./img/");
                            
                            $vehiculo = new Vehiculo($_POST["marca"], $_POST["patente"], $_POST["kms"], $rutaFoto);
                            //Guardo
                            Vehiculo::ModificarVehiculo($vehiculo);
                            
                            $listaVehiculos = Vehiculo::TraerVehiculos();
                            
                            $newResponse = json_encode($listaVehiculos);
                            
                            
                        }
                        else
                        {
                            $newResponse = json_encode("Vehículo inválido");
                        }
                        
                    }
                    echo $newResponse;
                    break;

    }
}
else if(isset($_GET['caso'])){
    $caso = $_GET['caso'];

    switch($caso)
    {
        // Se ingresa marca o patente, si coincide con algún registro del archivo se
        // retorna las ocurrencias, si no coincide se debe retornar “No existe xxx” (xxx es lo que se buscó) La búsqueda   
        // tiene que ser case insensitive
        case 'consultarVehiculo':

            $arrayVehiculos = array();
            
            if(isset($_GET["marca"]) || isset($_GET["patente"]))
            {
                $existeVehiculo = false;
                $newResponse;
                
                if(isset($_GET["marca"]))
                {
                    $marca = strtolower($_GET["marca"]);
                    $existeVehiculo = false;
                    $listaVehiculos = Vehiculo::TraerVehiculos();
                    
                    if(is_array($listaVehiculos))
                    {
                        foreach($listaVehiculos as $auxVehiculo)
                        {
                            if(strcasecmp(strtolower($auxVehiculo->marca),$marca) == 0)
                            {
                                $existeVehiculo = true;
                                array_push($arrayVehiculos, $auxVehiculo);
                                          
                            }
                        }

                    }
                    if($existeVehiculo == false)
                    {
                        $newResponse = json_encode("No existe $marca");
                    }
                    else
                    {
                        $newResponse = json_encode($arrayVehiculos);
                    }
                }
                else if(isset($_GET["patente"]))
                {
                    $patente = $_GET["patente"];
                    $existeVehiculo = false;
                    $listaVehiculos = Vehiculo::TraerVehiculos();
                    
                    if(is_array($listaVehiculos))
                    {
                        foreach($listaVehiculos as $auxVehiculo)
                        {
                            if($auxVehiculo->patente == $patente)
                            {
                                $existeVehiculo = true;
                                array_push($arrayVehiculos, $auxVehiculo);
                                break;
                                            
                            }
                        }
                    }
                    if($existeVehiculo == false)
                    {
                        $newResponse = json_encode("No existe $patente");
                    }
                    else
                    {
                        $newResponse = json_encode($arrayVehiculos);
                    }
                }             
                echo $newResponse;
            }
            break;

        // caso: sacarTurno (get): Se recibe patente, precio y fecha (día) y se debe guardar en el archivo
        // turnos.txt, fecha, patente, modelo, precio y tipo de servicio. 
        case 'sacarTurno':
            if(isset($_GET["patente"], $_GET["precio"], $_GET["fecha"]))
            {
                $patente = $_GET["patente"];
                $precio= $_GET["precio"];
                $fecha = $_GET["fecha"];
                
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
                    if($listaServicios != null && $vehiculo != null)
                    {
                        
                        $turno = new Turno($fecha, $patente, $vehiculo->marca,
                            $auxServicio->precio, $auxServicio->tipo);
                        Turno::GuardarTurno($turno);
                    }
                    
                    $listaTurnos = Turno::TraerTurnos();
                    $newResponse = json_encode($listaTurnos);
                                                    
                }
                else
                {
                    $newResponse = "La patente no existe";
                }    
            }
            echo $newResponse;
            break;

        // caso: turnos(get): Se devuelve un tabla con todos los servicios
        case 'turnos':
        
            $listaTurnos = Turno::TraerTurnos();
            $tablaTurnos = Turno::CrearTabla($listaTurnos);

            echo $tablaTurnos;
            
            break;
        
        // Puede recibir el tipo de servicio o la fecha y filtra la tabla de acuerdo al parámetro
        // pasado.
        case "servicio":
            if(isset($_GET["tipo"]) || isset($_GET["fecha"]))
            {
                if(isset($_GET["tipo"]))
                {
                    $filtro = $_GET["tipo"];
                    
                    $listaTurnos = Turno::TraerTurnos();

                    $tablaTurnos = Turno::FiltrarLista($listaTurnos, $filtro);
                }
                else
                {
                    $filtro = $_GET["fecha"];
                    $listaTurnos = Turno::TraerTurnos();

                    $tablaTurnos = Turno::FiltrarLista($listaTurnos, $filtro);
                }
            }
            echo $tablaTurnos;
            break;
        
        // vehiculos(get): Mostrar una tabla con todos los datos de los vehículos, incluida la foto.
        case 'vehiculosMostrar':

            $listaVehiculos = Vehiculo::TraerVehiculos();
            $tablaVehiculos = Vehiculo::CrearTabla($listaVehiculos);

            echo $tablaVehiculos;
            
            break;
    }   
}

?>