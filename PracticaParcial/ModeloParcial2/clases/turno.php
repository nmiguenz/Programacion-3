<?php

include_once 'funciones/parser.php';

class Turno{
    public $fecha;    
    public $patente;
    public $marca;
    public $modelo;
    public $precio;
    public $tipoServicio;

    function _construct($fecha,$patente,$marca,$modelo,$precio,$tipoServicio){
        $this->fecha=$fecha;
        $this->patente=$patente;
        $this->marca=$marca;
        $this->modelo=$modelo;
        $this->precio=$precio;
        $this->tipoServicio=$tipoServicio;
    }
    
    function sacarTurno($arrayDeParametros){
        $valorRetornado=false;
        $arrayDeParametrosTurnos = leer("turnos.txt");
        $arrayDeParametrosVehiculos = leer("vehiculos.txt");
        $arrayDeParametrosServicio = leer("tiposServicio.txt");
        
        if(isset($arrayDeParametrosVehiculos) && isset($arrayDeParametrosServicio)){
            if(isset($arrayDeParametrosTurnos)){ 
                //busco que el id ingresado ya no este asignado a otro turno
                foreach($arrayDeParametrosTurnos as $key => $val ){
                    $auxArrayTurnos= (array)$val;
                    if(isset($auxArrayTurnos)){
                        if($auxArrayTurnos['patente']===$arrayDeParametros['patente']){
                            $valorRetornado=true;
                            break;
                        }
                    }
                }
            }
            if(!$valorRetornado || !$arrayDeParametrosTurnos ){
                //si id ingresado no esta asignado a otro turno, obtengo los valores del vehiculo
                foreach($arrayDeParametrosVehiculos as $key => $val){
                    $auxArrayVehiculos= (array)$val;
                    //$valorRetornado=false;
                    if($auxArrayVehiculos){
                        if($auxArrayVehiculos['patente']===$arrayDeParametros['patente']){
                            $valorRetornado=true;                                    
                            foreach($arrayDeParametrosServicio as $key => $val){
                                $auxArrayServicio= (array)$val;
                                //$valorRetornado=false;
                                if($auxArrayServicio){
                                    if($auxArrayServicio['id']===$arrayDeParametros['idServicio']){
                                        echo ('Se guardo el obj');
                                        //$fecha, $patente,$marca,$modelo, $precio,$tipoServicio
                                        $this->_construct($auxArrayServicio['fecha'],$arrayDeParametros['patente'],
                                                $auxArrayVehiculos['marca'],$auxArrayVehiculos['modelo'],$auxArrayVehiculos['precio'],$arrayDeParametros['idServicio']);
                                        guardar("turnos.txt", $this, "a");
                                        $valorRetornado=true;
                                        break;
                                    }
                                }
                            }
                        }
                    }   
                }
            }                
            
        }       
        if($valorRetornado){
            echo('no se pudo guardar: porque el obj esta repetido');
        }       
    }
    /**
     * Funcion que genera una tabla,
     * en base a los datos ingresados.
     */
    function turnos(){
        $valorRetornado=false;
        $arrayDeParametrosTurnos = leer("turnos.txt");
        $tabla;
        if( $arrayDeParametrosTurnos){
            $tabla="<table border='1'>
                <caption>Turnos</caption>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Patente</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Precio</th>
                        <th>Tipo Servicio</th>
                    </tr>
                </thead>
            <tbody>";
            foreach($arrayDeParametrosTurnos as $key => $valor){
                $auxArray= (array)$valor;
                $tabla= $tabla."<tr>
                                <td>".$auxArray['fecha']."</td>
                                <td>".$auxArray['patente']."</td>
                                <td>".$auxArray['marca']."</td>
                                <td>".$auxArray['modelo']."</td>
                                <td>".$auxArray["precio"]."</td>
                                <td>".$auxArray["tipoServicio"]."</td>
                                </tr>";
            }
            $tabla = $tabla."</tbody>
                            </table>";
            echo $tabla;
        }
        else{
            echo('No se pudo abrir el archivo');
        }
    }


    function inscripciones($arrayDeParametros){
        $valorRetornado=false;
        $arrayDeParametrosTurnos = leer("turnos.txt");
        $tabla;
        if( $arrayDeParametrosTurnos){
            $tabla="<table border='1'>
                    <caption>Turnos</caption>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Patente</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Precio</th>
                            <th>Tipo Servicio</th>
                        </tr>
                    </thead>
                <tabody>";            
            foreach ($arrayDeParametrosTurnos as $key => $val) {
                $auxArray= (array)$val;
                if($auxArray['tipoServicio'] === $arrayDeParametros['tipoServicio'] || $auxArray['fecha'] === $arrayDeParametros['fecha'] ){
                    $valorRetornado=true;
                    $tabla= $tabla."<tr>
                                <td>".$auxArray['fecha']."</td>
                                <td>".$auxArray['patente']."</td>
                                <td>".$auxArray['marca']."</td>
                                <td>".$auxArray['modelo']."</td>
                                <td>".$auxArray["precio"]."</td>
                                <td>".$auxArray["tipoServicio"]."</td>
                                </tr>";
                }
            }
            if($valorRetornado){
                $tabla = $tabla."</tbody>
                </table>";
                echo $tabla;
            }
            else{
                echo('No se encontraron ocurrencias.');
            }
        }
    }
}
?>