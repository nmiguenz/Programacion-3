<?php
class Vehiculo{
    public $marca;
    public $modelo;
    public $patente;
    public $precio;

    function _construct($marca,$modelo,$patente,$precio){
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->patente = $patente;
        $this->precio = $precio;
    }
    /*
    public function __toString(){
        return $this->marca."-".$this->modelo."-".$this->patente."-".$this->precio;
    } 
    */
}
?>