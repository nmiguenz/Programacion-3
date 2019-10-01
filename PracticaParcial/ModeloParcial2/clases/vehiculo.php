<?php

include_once 'funciones/parser.php';

class Vehiculo{
    public $marca;
    public $modelo;
    public $patente;
    public $precio;
    public $foto;

    function _construct($marca,$modelo,$patente,$precio,$foto){
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->patente = $patente;
        $this->precio = $precio;
        $this->foto = $foto["name"];
        move_uploaded_file($foto["tmp_name"],"./imagenes/".$this->foto);
    }
    
    function cargarVehiculo($arrayDeParametros, $foto){
        $flag = false;
        $arrayParametrosComparacion = leer("vehiculos.txt");
        $array;

        if($arrayParametrosComparacion){
            foreach($arrayParametrosComparacion as $key => $val){
                $array = (array)$val;
                
                if($array){
                    
                    if($array["patente"]===$arrayDeParametros["patente"]){
                        $flag = true;
                        break;
                    }
                }
            }
        }

        //Si no encuentra la patente devuelve false y guarda el auto en el archivo
        if(!$flag){
            $this->_construct($arrayDeParametros["marca"],$arrayDeParametros["modelo"], $arrayDeParametros["patente"],$arrayDeParametros["precio"],$foto);
            guardar("vehiculos.txt", $this, "a");
            echo("Se guardo el auto");
        }
        else{
            echo("No se guardó el auto porque la patente esta repetida");
        }

    }

    function consultarVehiculo($arrayDeParametros){
        $flag = false;
        $arrayParametrosComparacion = leer("vehiculos.txt");
        $array;
        
        if($arrayParametrosComparacion){
            foreach($arrayParametrosComparacion as $key => $val){
                $array = (array)$val;
                
                if($array){
                    if(strtolower($array["patente"])===strtolower($arrayDeParametros["patente"]) ||
                        strtolower($array["modelo"])===strtolower($arrayDeParametros["modelo"]) ||
                        strtolower($array["marca"])===strtolower($arrayDeParametros["marca"])){
                        $flag = true;
                        echo("Patente ".$array['patente'].'Marca '.$array['marca'].'Modelo '.$array['modelo']);
                    }
                }
            }
        }
        if(!$flag){
            echo('No existe:'.' Patente:'.$arrayDeParametros['patente'].' Marca:'.$arrayDeParametros['marca'].' Modelo:'.$arrayDeParametros['modelo']);
        }
    }

    function modificarVehiculo($arrayDeParametros, $foto){
        $nombreArchivo="vehiculos.txt";    
        //obtengo el array
        $arrayObtenido= leer($nombreArchivo);
        //Obtengo el array sin el indice(id).
        if(isset($arrayObtenido)){
            $auxArrayIdBorrado= borrar($arrayObtenido,$arrayDeParametros['patente'], $foto);
        }  
        //guardo todo de nuevo en el archivo.
        if(isset($auxArrayIdBorrado)){            
            $this->_construct($arrayDeParametros['marca'],$arrayDeParametros['modelo'],$arrayDeParametros['patente'],$arrayDeParametros['precio'],$foto); 
            guardar("vehiculos.txt", $this, "w");
            foreach ($auxArrayIdBorrado as $key => $auxArray) {
                $value= (array)$auxArray;
                $this->_construct($value['marca'],$value['modelo'],$value['patente'],$value['precio'],$value);                
                guardar("vehiculos.txt", $this, "a");
            }
            echo ("\n".'Se modifico el array correctamente.');
        }
        else{
            echo ('No se pudo modificar el array!!!');
        }
    }
}

function vehiculos(){
        
    $valorRetornado=false;
    $arrayDeParametrosVehiculos = leer("vehiculos.txt");
    $tabla;
    if( $arrayDeParametrosVehiculos){
        $tabla="<table border='1'>
            <caption>Vehiculos</caption>
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Patente</th>
                    <th>Precio</th>
                    <th>Foto</th>
                </tr>
            </thead>
        <tabody>";
        foreach($arrayDeParametrosVehiculos as $key => $val){
            $auxArray= (array)$val;
            $tabla= $tabla."<tr>
                            <td>".$auxArray['marca']."</td>
                            <td>".$auxArray['modelo']."</td>
                            <td>".$auxArray['patente']."</td>
                            <td>".$auxArray['precio']."</td>
                            <td><img style='width: 100px; height: 100px;' src='../imagenes/".$auxArray['foto']."'></td>
                            </tr>";
        }
        $tabla = $tabla."</tbody>
                        </table>";
        echo $tabla;
    }
    else{
        echo('No se pudo abrir el archivo');
    }
    
}

?>