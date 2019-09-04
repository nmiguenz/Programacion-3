<?php

 class funciones 
 {

    function Guardar($nombreArchivo, $obj){


        $strigJs = json_encode($obj);

        $archivo = fopen($nombreArchivo, "a");

        fwrite($archivo, $strigJs.PHP_EOL);

        fclose($archivo);
    }


    function Leer ($nombreArchivo){
        $arrayLineas = array();
         
         $file = fopen($nombreArchivo, "r");
         while(!feof($file)){
         
             $obj = json_decode(fgets($file));
             array_push($arrayLineas, $obj);
         }
         fclose($file);
    
        return $arrayLineas;
    } 


    function borrar($arrayLineas, $id){
        array_filter();
    }

    function modificar($arrayLineas, $objetoPersona){
        $var=array_search($objetoPersona->id,$arrayLineas);
        if ($var) {
            $arrayLineas[$var]->nombre=$objetoPersona->nombre;
        }
        else
        {
            echo "no esta el legajo";
        }
        return $arrayLineas;
    }

 }
?>