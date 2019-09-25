<?php
Class Materia {
    private  $nombre ; 
    private $código; 
    private $cupoAlumnos;
    private $aula;

    function __construct($nombre, $codigo, $cupoAlumnos, $aula){
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->cupoAlumnos = $cupoAlumnos;
        $this->aula = $aula;
    }
    
    public function __toString(){
        return $this->nombre."-".$this->apellido."-".$this->foto.PHP_EOL;
    } 
}
?>