<?php


function moverArchivos($nombreArchivo, $destino){
    $partes_ruta = pathinfo($nombreArchivo);
    echo 'entro';
    $extensionAux = explode(".", $partes_ruta['basename']);
    echo 'entro2';
    $extension = end($extensionAux);
    echo 'entro3';
    $name = "img_".random_int(1,100);
    $name.= ".".$extension;
    echo 'entro4';
    if (move_uploaded_file($name, $destino)) 
    {
        echo "Uploaded!","\n";
    } 
    else {
        print "falló";
    }
    return 0;
}
?>