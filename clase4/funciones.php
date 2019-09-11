<?php


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
    
    
     function Borrar ($id,$arrayLineas){
    
         foreach (array_keys($arrayLineas, $id) as $key) 
         {
             unset($arrayLineas[$key]);
         }
         echo "Removiendo: ".$id."\n\n";
         return $arrayLineas;
     }
    
     function modificar($arrayLineas, $objetoPersona){
         
     }

     function moverArchivos($nombreArchivo, $destino){
        //archivo temporal
        //$nombreArchivo = $_FILES["foto"]["tmp_name"];
        //$destino = "imagenCopia.jpg"
        if (move_uploaded_file($nombreArchivo, $destino)) 
        {
            echo "Uploaded!","\n";
        } 
        else {
            print "falló";
        }
        //Saber extensión
        $extensionArch = $_FILES["foto"]["name"];
        //muestra la información del path
        $path_parts = pathinfo($extensionArch); 
        //$path_parts['extension'];
        //echo $path_parts['dirname'], "\n"; 
        //echo $path_parts['basename'], "\n"; 
        $nombreArchivoSinExtension = $path_parts['filename'];
        //explode(separator,string,limit)
        $extensionAux = explode(".",$path_parts['basename']);
        $extension = end($extensionAux);
        //Para saber el final de un array
        //end($nombreArray);
        $nombreArchivoCompleto = $nombreArchivoSinExtension.random_int(1,100).".".$extension;
        //echo $nombreArchivoCompleto;
        }

?>