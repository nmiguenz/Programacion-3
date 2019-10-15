<?php

function leer($nombreArchivo){
    $arrayLineas = array(); 
    $file = fopen($nombreArchivo, "r");
    if($file){
        while(!feof($file)){
        
            $obj = json_decode(fgets($file));
            array_push($arrayLineas, $obj);
        }
        fclose($file);
    }
   return $arrayLineas;
}

function guardar($nombreArchivo, $obj,$mode){
         
         $strigJs = json_encode($obj);
         // $mode a  w
         $archivo = fopen($nombreArchivo, $mode);
    
         fwrite($archivo, $strigJs.PHP_EOL);
    
         fclose($archivo);
}

// A partir de este punto, se debe guardar en un archivo info.log la información de cada petición recibida
// por la API (ruta, metodo, hora).
function guardarInfo($request){
    $uri = $request->getUri();
    $method = $request->getMethod();
    $hoy = date("F j, Y, g:i a");
    $string = "{Ruta: ".$uri->getBaseUrl().$uri->getPath().", Metodo: ".$method.", fecha: ".$hoy.'}';
    $archivo = fopen("info.log", "a");
    fwrite($archivo, $string.PHP_EOL);
    fclose($archivo);
}

function borrar ($arrayLineas,$id){
    $valorRetornado=false;   
    foreach ($arrayLineas as $key => $value) {
        $auxArray= (array)$value; 
        if($auxArray){
            if($auxArray["id"] === $id){
                // if($foto["name"] !== $auxArray["foto"] ){
                //     var_dump("./imagenes/".$auxArray["foto"]);
                //     copy("./imagenes/".$auxArray["foto"], './backUpFotos/'.$auxArray["patente"].$auxArray["foto"]);
                // }
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