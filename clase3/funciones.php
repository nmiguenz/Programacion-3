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

?>