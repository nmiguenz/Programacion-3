<?php
function guardar($nombreArchivo, $obj){
         
         $strigJs = json_encode($obj);
    
         $archivo = fopen($nombreArchivo, "a");
    
         fwrite($archivo, $strigJs.PHP_EOL);
    
         fclose($archivo);
}
?>