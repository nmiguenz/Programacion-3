<?php
include_once "funciones.php";

$obj = new stdClass();
$r = new funciones();

$obj->nombre = $_GET["nombre"];
$obj->apellido = $_GET["apellido"];
$obj->legajo = $_GET["legajo"];

//$r->Guardar("nombreArchivo.txt",$obj);
var_dump($r->Leer("nombreArchivo.txt"));
?>
 