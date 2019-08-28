<?php

class Persona{
    public function __construct($nombre, $apellido){
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }


    public function saludar(){
      return json_encode($this);
    }

}
?>