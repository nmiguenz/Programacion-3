<?php
class Venta
{
    public $id;
    public $email;
    public $precio;
    public $tipo;
    public $cantidad;
    public $sabor;
    
    public function __construct($precio, $tipo, $cantidad, $sabor, $email)
    {
        $this->id = Venta::CrearIdAutoincremental();
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->sabor = $sabor;
        $this->email = $email;
    }

    public static function CrearIdAutoincremental()
    {
        $listaVentas = Venta::TraerVentas();
        if($listaVentas != null)
        {
            $id = count($listaVentas) + 1;
        }
        else
        {
            $id = 1;
        }
        return $id;
    }

    public static function GuardarVenta($venta)
    {
        $ruta = "./Venta.txt";
        $guardo = false;       
        Archivo::GuardarUno($ruta, $venta);
        $guardo = true;
        
          
        return $guardo;
    }

    public static function TraerVentas()
    {
        $ruta = "./Venta.txt";
        
        $listaVentas = Archivo::LeerArchivo($ruta);
        
        return $listaVentas;
    }
}
?>