<?php
include './alumno.php';
/*
if(!isset($_SESSION['SesionAlumno'])){
    $_SESSION['SesionAlumno'] = array() ;
}
*/
class Funciones extends Alumno{

    function traerListado(){
        $alumno = new Alumno($_GET['nombre'],$_GET['apellido']);
        array_push($_SESSION['SesionAlumno'], $alumno);
        return $_SESSION['SesionAlumno'];
    }
    
    function guardarLista(){
        array_push($_SESSION['SesionAlumno']);
    }
}
?>