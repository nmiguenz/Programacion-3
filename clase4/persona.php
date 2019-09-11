<?php

class Persona{
    public function __construct($nombre, $apellido, $legajo){
        $this-> nombre = $nombre;
        $this-> apellido = $apellido;
        $this-> legajo = $legajo;
    }

    function saludar(){
       echo "hola ", $this->nombre;
    }
}
?>