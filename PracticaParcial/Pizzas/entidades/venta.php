<?php

require_once 'pizza.php';
require_once 'funciones/parser.php';

class Venta{
    public $email;
    public $sabor;
    public $tipo;
    public $cantidad;
    public $precioVenta;

    function _construct($email,$sabor,$tipo,$cantidad,$precioVenta,$id){
        $this->email = $email;
        $this->sabor = $sabor;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->precioVenta = $precioVenta;
        $this->id=++$id;
    }

// Ruta: ventas (POST). Recibe el email del usuario y el sabor,tipo y cantidad ,si el item existe en Pizza.xxx,
// y hay stock guardar en el archivo de texto Venta.xxx todos los datos , más el precio de la venta, un id
// autoincremental y descontar la cantidad vendida. Si no cumple las condiciones para realizar la venta, informar el
// motivo.
    function cargarVenta($arrayDeParametros){
        $flag = false;
        $arrayParametrosComparacion = leer("Pizza.txt");
        $arrayVentas = leer("venta.txt");
        $array;
        //strval

        if($arrayParametrosComparacion){
            foreach($arrayParametrosComparacion as $key => $val){
                $array = (array)$val;
                
                if($array){
                    if($array["tipo"]===$arrayDeParametros["tipo"] && $array["sabor"]===$arrayDeParametros["sabor"]){
                        if((int)$arrayDeParametros["cantidad"] <= (int)$array["cantidad"]) {
                            $auxIdPizza = $array['id'];
                            $precio = $array['precio'];
                            $tipo = $array['tipo'];
                            $sabor = $array['sabor'];
                            $foto = $array['foto'];
                            $foto2 = $array['foto2'];
                            $stock = (int)$array["cantidad"] - (int)$arrayDeParametros["cantidad"];
                            $precioVenta = (float)$array['precio']*(int)$arrayDeParametros['cantidad'];
                            $flag = true;
                            break;
                        }
                    }
                    
                }
                
            }
        }

        if($flag){
            if(isset($arrayVentas)){
                foreach($arrayVentas as $key => $val){
                    $array = (array)$val;
                    
                    if(isset($array)){
                        $auxId = $array['id'];
                    }
                }
            }
            else{
                $auxId = 0;
            }
            //faltan validaciones
            $this->_construct($arrayDeParametros['email'],$arrayDeParametros["sabor"],$arrayDeParametros["tipo"], $arrayDeParametros["cantidad"],strval($precioVenta),$auxId);
            guardar("venta.txt", $this, "a");
            echo("Se guardo la venta");
            $auxArrayPizza = borrar($arrayParametrosComparacion,$auxIdPizza);
            $pizza = new Pizza();
            $pizza->_construct($precio,$tipo,strval($stock),$sabor,$foto,$foto2,$auxIdPizza);
            array_push($auxArrayPizza, $pizza);
            $nuevaPizza = new Pizza();
            $nuevaPizza->cargarPizzas($auxArrayPizza);

        }
        else{
            echo("No se guardó el objeto venta");
        }
    }
}

?>