<?php
    class Turno
    {
        public $fecha;
        public $patente;
        public $marca;
        public $precio;
        public $tipoServicio;

        public function __construct($fecha, $patente, $marca, $precio, $tipoServicio)
        {
            $this->fecha = $fecha;
            $this->patente = $patente;
            $this->marca = $marca;
            $this->precio = $precio;
            $this->tipoServicio = $tipoServicio;
            
        }

        public static function TraerTurnos()
        {
            $ruta = "./turnos.txt";
            
            $listaTurnos = Archivo::LeerArchivo($ruta);
            return $listaTurnos;
        }

        public static function GuardarTurno($turno)
        {
            $ruta = "./turnos.txt";
            
            Archivo::GuardarUno($ruta, $turno);
           
            
        }

        public static function CrearTabla($listaTurnos)
        {
            $tablaTurnos = "<table>
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Patente</th>
                                    <th>Marca</th>
                                    <th>Precio</th>
                                    <th>Servicio</th>
                                </tr>     
                            </thead>
                            <tbody>";
            
            foreach($listaTurnos as $turno)
            {
                $tablaTurnos .= "<tr>
                                    <td>" . $turno->fecha . "</td>
                                    <td>" . $turno->patente . "</td>
                                    <td>" . $turno->marca . "</td>
                                    <td>" . $turno->precio . "</td>
                                    <td>" . $turno->tipoServicio . "</td>    
                                </tr>";
            }
                                    
            $tablaTurnos .=  "</tbody></table>";
            return $tablaTurnos;
        }

        public static function FiltrarLista($listaTurnos, $filtro)
        {
            $listaFiltrada = array();
            
            if(is_array($listaFiltrada))
            {
                foreach($listaTurnos as $turno)
                {
                    if(strcasecmp($turno->fecha, $filtro) == 0 || strcasecmp($turno->tipoServicio, $filtro) == 0)
                    {
                        array_push($listaFiltrada, $turno);
                    }
                }
            }
            $tablaTurnos = Turno::CrearTabla($listaFiltrada);
            
            return $tablaTurnos;
        }

        // public static function OrdenarLista($listaTurnos, $filtro)
        // {
            
        //     switch($filtro)
        //     {
        //         case "fecha":
        //         usort($listaTurnos, array("vehiculoApi", "compararFecha"));
        //         break;
        //         case "servicio":
        //         usort($listaTurnos, array("vehiculoApi", "compararServicio"));
        //         break;
        //     }
        //     return $listaTurnos;    
            
        // }

        // //funiones que ordenan
        // public static function compararFecha($elementoA, $elementoB)
        // {
        //     return strcasecmp($elementoA->fecha, $elementoB->fecha);
        // }
        
        // public static function compararServicio($elementoA, $elementoB)
        // {
        //     $retorno = 1;
        //     if($elementoA->tipo < $elementoB->tipo)
        //     {
        //         $retorno = -1;
        //     }
        //     else if($elementoA->tipo == $elementoB->tipo)
        //     {
        //         $retorno = 0;
        //     }
        //     return $retorno;
        // }
    }
?>