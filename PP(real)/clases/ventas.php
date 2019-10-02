<?php

require_once 'funciones/funciones.php';

class Ventas{
    public $email;
    public $sabor;
    public $tipo;
    public $cantidad;

    function _construct($email,$sabor,$tipo,$cantidad){
        $this->email=$email;
        $this->tipo=$tipo;
        $this->cantidad=$cantidad;
        $this->sabor=$sabor;
    }

}
?>