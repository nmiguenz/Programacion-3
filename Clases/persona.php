<?php

class Persona{
    public function __construct($nombre){
        $this-> nombre = $nombre;
    }

    function saludar(){
       echo "hola ", $this->nombre;
    }
}
?>