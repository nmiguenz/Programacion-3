<?php

include_once 'funciones/parser.php';
// pizzas (POST): se ingresa precio, Tipo (“molde” o “piedra”), cantidad( de unidades),sabor
// (muzza;jamón; especial), precio y dos imágenes (guardarlas en la carpeta images/pizzas y cambiarles el nombre
// para que sea único). Se guardan los datos en en el archivo de texto Pizza.xxx, tomando un id autoincremental
// como identificador, la combinación tipo - sabor debe ser única.

class Pizza{

    public $precio;
    public $tipo;
    public $cantidad;
    public $sabor;
    public $foto;
    public $foto2;
    public $id;

    function _construct($precio,$tipo,$cantidad,$sabor,$foto,$foto2,$id){
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->sabor = $sabor;
        $this->id=++$id;

        //Guarda un archivo con un nombre unico.
        if(isset($foto["name"])){
            $Arch = $_FILES["foto"]["name"];
            $path_parts = pathinfo($Arch); 
            $nombreArchivoSinExtension = $path_parts['filename']; //guarda la primera parte o nombre del archivo
            $extensionAux = explode(".",$path_parts['basename']); 
            $extension = end($extensionAux);
            $nombreArchivoCompleto = 'img_'.random_int(1,100).".".$extension;
            $this->foto = $nombreArchivoCompleto;
            move_uploaded_file($foto["tmp_name"],"./images/pizzas".$this->foto);
        }
        else{
            $this->foto=$foto;
        }
        
        
        if(isset($foto2["name"])){
            $extensionArch = $_FILES["foto2"]["name"];
            $path_parts = pathinfo($extensionArch); 
            $nombreArchivoSinExtension = $path_parts['filename'];
            $extensionAux = explode(".",$path_parts['basename']);
            $extension = end($extensionAux);
            $nombreArchivoCompleto = $nombreArchivoSinExtension.random_int(1,100).".".$extension;
            $this->foto2 = $nombreArchivoCompleto;
            move_uploaded_file($foto2["tmp_name"],"./images/pizzas".$this->foto2);
        }
        else{
            $this->foto2=$foto2;
        }
    }

    function cargarPizza($arrayDeParametros, $foto,$foto2){
        $flag = false;
        $arrayParametrosComparacion = leer("Pizza.txt");
        $array;

        if($arrayParametrosComparacion){
            foreach($arrayParametrosComparacion as $key => $val){
                $array = (array)$val;
                
                if($array){
                    
                    if($array["tipo"]===$arrayDeParametros["tipo"] && $array["sabor"]===$arrayDeParametros["sabor"] ){
                        $flag = true;
                        break;
                    }
                    $auxId = $array['id'];
                }
                
            }
        }
        else{
            $auxId = 0;
        }

        //Si no encuentra la pizza devuelve false y guarda la nueva pizza en el archivo
        if(!$flag){
            //faltan validaciones
            $this->_construct($arrayDeParametros["precio"],$arrayDeParametros["tipo"], $arrayDeParametros["cantidad"],$arrayDeParametros["sabor"],$foto, $foto2,$auxId);
            guardar("Pizza.txt", $this, "a");
            echo("Se guardo la pizza");
        }
        else{
            echo("No se guardó la pizza porque el tipo y sabor estan repetidos");
        }
    }

// pizzas: (GET): Recibe Sabor y Tipo, si coincide con algún registro del archivo Pizza.xxx, retornar la
// cantidad de producto disponible, de lo contrario informar si no existe el tipo o el sabor. La consulta debe ser case
// insensitive.
    function consultarPizza($arrayDeParametros){
        $flag = false;
        $arrayParametrosComp = leer("Pizza.txt");
        if(isset($arrayParametrosComp)){
            foreach ($arrayParametrosComp as $key => $value) {
                $array = (array)$value;
                if($array){
                    if(strtolower($array['sabor'])===strtolower($arrayDeParametros['sabor']) && strtolower($array['tipo'])===strtolower($arrayDeParametros['tipo'])){
                        echo('Cantidad disponible:'.$array['cantidad']);
                        $flag = true;
                    }
                }
            }
            if(!$flag){
                echo("No existe la pizza sabor: ".$arrayDeParametros['sabor'].' tipo:'.$arrayDeParametros['tipo']);
            }
        }
    }


    function cargarPizzas($arrayDeParametros){
        $flag = false;
        foreach($arrayDeParametros as $key => $val){
            $array = (array)$val;    
            if(isset($array)){
                $this->_construct($array["precio"],$array["tipo"], $array["cantidad"],$array["sabor"],$array['foto'], $array['foto2'],$array['id']);
                if(!$flag){
                    guardar('Pizza.txt',$this, 'w');
                    $flag=true;
                }
                else{
                    gurdar('Pizza.txt', $this,'a');
                }               

            }
            
        }
    }


}
?>