<?php

include_once 'funciones/parser.php';

class Servicio{
    public $id;
    public $tipo;
    public $precio;
    public $demora;

    function _construct($id, $tipo, $precio, $demora){
        $this ->id = $id;
        $this ->tipo = $tipo;
        $this ->precio = $precio;
        $this ->demora = $demora;
    }

    function cargarTipoServicio($arrayDeParametros){
        $flag = false;
        $flagTipo = false;
        $array;
        $arrayDeComparacion = leer('tiposServicios.txt');

        //Compara los array y devuelve el INDICE si es encontrado
        if($arrayDeComparacion){
            foreach ($arrayDeComparacion as $key => $value) {
                $array = (array)$value;

                if(isset($array)){
                    if($array['id']===$arrayDeParametros['id']){
                        $flag = true;
                        break;
                    }
                }
            }
        }
        
        //Si el tipo no responde al rango, no se setea
        if($arrayDeParametros['tipo'] !== "10000" && $arrayDeParametros['tipo'] !== "20000" && $arrayDeParametros['tipo'] !== "50000"){
            $flagTipo = true;
            echo('error, el TIPO debe ser: 10000 o 20000 o 50000'."\n");
        }

        if(!$flag && !$flagTipo){
            $this->_construct($arrayDeParametros['id'],$arrayDeParametros['tipo'], $arrayDeParametros['precio'], $arrayDeParametros['demora']);
            guardar('tiposServicios.txt',$this,'a');
            echo('Se guardó el servicio');
        }
        else{
            echo("No se guardo el servicio porque esta repetido o se encuentra fuera de rango");
        }
    }
}

?>