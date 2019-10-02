<?php

require_once 'funciones/funciones.php';

class Pizza{
    public $precio;
    public $tipo;
    public $cantidad;
    public $sabor;
    public $foto;
    public $foto2;
    public $id;


    function _construct($id,$precio, $tipo, $cantidad, $sabor, $foto, $foto2){
        $this->precio=$precio;
        $this->tipo=$tipo;
        $this->cantidad=$cantidad;
        $this->sabor=$sabor;
        $this->id= $id;
        
        if($foto["name"]){
            $extensionArch = $_FILES["foto"]["name"];
            $path_parts = pathinfo($extensionArch); 
            $nombreArchivoSinExtension = $path_parts['filename'];
            $extensionAux = explode(".",$path_parts['basename']);
            $extension = end($extensionAux);
            $nombreArchivoCompleto = $nombreArchivoSinExtension.random_int(1,100).".".$extension;
            $this->foto = $nombreArchivoCompleto;
            move_uploaded_file($foto["tmp_name"],"./imagenes/pizzas".$this->foto);
        }
        else{
            $this->foto=$foto["foto"];
        }
        if($foto2["name"]){
            $extensionArch = $_FILES["foto"]["name"];
            $path_parts = pathinfo($extensionArch); 
            $nombreArchivoSinExtension = $path_parts['filename'];
            $extensionAux = explode(".",$path_parts['basename']);
            $extension = end($extensionAux);
            $nombreArchivoCompleto = $nombreArchivoSinExtension.random_int(1,100).".".$extension;
            $this->foto2 = $nombreArchivoCompleto;
            move_uploaded_file($foto2["tmp_name"],"./imagenes/pizzas".$this->foto2);
        }
        else{
            $this->foto2=$foto2["foto"];
        }
        
    }

    function cargarPizza($arrayDeParametros,$uploadedFile,$uploadedFile2){
        $valorRetornado=false;
        $auxArray;
        $arrayDeParametrosDos = leer("pizza.txt");
        
        
        //busca el valor en ambos arrays y
        //si lo encuentra devuelve el indice.
        if($arrayDeParametrosDos){
            foreach($arrayDeParametrosDos as $key => $val){
                $auxArray= (array)$val;
                //$valorRetornado=false;
                if($auxArray){
                    if($auxArray['tipo']===$arrayDeParametros['tipo'] && $auxArray['sabor']===$arrayDeParametros['sabor']){
                        $valorRetornado=true;
                        break;
                    }
                }   
            }
        }     
        //si no encuentra devuelve false,
        //entonces guardo en el archivo
        if(!$valorRetornado){
            $this->_construct($arrayDeParametros['id'],$arrayDeParametros['precio'],$arrayDeParametros['tipo'],$arrayDeParametros['cantidad'],$arrayDeParametros['sabor'],$uploadedFile, $uploadedFile2);                
            echo ('Se guardo la nueva pizza porque no esta repetida');
            guardar("pizza.txt", $this, "a");
        }
        else{
            echo('no se pudo guardar: porque esta repetida');
        }        
    }

    function consultarPizza($arrayDeParametros){
        $flag = false;
        $arrayParametrosComp = leer("pizza.txt");
        if(isset($arrayParametrosComp)){
            foreach ($arrayParametrosComp as $key => $value) {
                $array = (array)$value;
                if($array){
                    if(strtolower($array['sabor'])===strtolower($arrayDeParametros['sabor']) || strtolower($array['tipo'])===strtolower($arrayDeParametros['tipo'])){
                        echo('Cantidad disponible:'.$array['cantidad']);
                        $flag = true;
                    }
                }
            }
            if(!$flag){
                echo("No existe el sabor: ".$arrayDeParametros['sabor'].' o tipo:'.$arrayDeParametros['tipo']);
            }
        }
    }
   
}
?>