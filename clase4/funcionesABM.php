<?php

    include alumno.php;

    function Alta(){

        $parametros = array();

        if (isset ($_POST['nombre'])){
            $nombre = $_POST['nombre'];
            array_push($parametros, $nombre);
        
        if(isset ($_POST['apellido'])){                   
            $apellido = $_POST['apellido'];
            array
        }
                
        else if (isset ($_POST['legajo'])){
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $legajo = $_POST['legajo'];

            $parametros = array($nombre, $apellido, $legajo);
        }
        
        $alumno = new Alumno($parametros);
    }

?>