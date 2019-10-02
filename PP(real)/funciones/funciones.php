<?php
/**
 * funcion que guardar un objeto,
 * en un archivo, bajo el formato JSON
 */
function guardar($nombreArchivo, $obj, $mode){
        $strigJs = json_encode($obj);
        //$mode = [a | w];
        $archivo = fopen($nombreArchivo, $mode);    
        fwrite($archivo, $strigJs.PHP_EOL);
        //fwrite($archivo, $strigJs);    
        fclose($archivo);
}
/**
 * funcion que devuelve un array y 
 * parsea a obj json
 */
function leer ($nombreArchivo){
    $arrayLineas = array();    
    $file = fopen($nombreArchivo, "r");
    if($file){
        while(!feof($file)){    
            $obj = json_decode(fgets($file));
            if($obj){
                array_push($arrayLineas, $obj);
            }            
        }
        fclose($file);
    }   
    return $arrayLineas;
} 
/**
 * recibe un array y un id, 
 * si lo encuentra elimina el indice del array.
 * devuelve un array sin lo que coinciden con el id.
 */
function borrar ($arrayLineas,$id,$foto){
    $valorRetornado=false;   
    foreach ($arrayLineas as $key => $value) {
        $auxArray= (array)$value; 
        if($auxArray){
            if($auxArray["patente"] === $id){
                var_dump($foto["name"]);
                var_dump($auxArray["foto"]);
                if($foto["name"] !== $auxArray["foto"] ){
                    var_dump("./imagenes/".$auxArray["foto"]);
                    //cambia de directorio la imagen vieja a backUpFotos, pero no la conserva.
                    //rename("./imagenes/".$auxArray["foto"], './backUpFotos/'.$auxArray["patente"].$auxArray["foto"]);
                    //cambia de directorio la imagen vieja a backUpFotos, pero la conserva en ambos directorios.
                    copy("./imagenes/".$auxArray["foto"], './backUpFotos/'.$auxArray["patente"].$auxArray["foto"]);
                }
                unset($arrayLineas[$key]);
                $valorRetornado=true;
                break;
            }
        }     
    }     
    if($valorRetornado){
        echo("Se Removio: ".$id."\n\n");
    }
    else{
        echo('No se encontro'."\n\n");
        $arrayLineas=null;
    }    
    return $arrayLineas;
}
?>