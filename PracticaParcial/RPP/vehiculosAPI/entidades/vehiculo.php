<?php
    class Vehiculo
    {
        public $patente;
        public $marca;
        public $kms;
        public $rutaFoto;
        
        public function __construct( $patente,$marca, $kms, $imagenes)
        {
            $this->marca = $marca;
            $this->patente = $patente;
            $this->kms = $kms;
            $this->rutaFoto = Archivo::GuardarArchivoTemporal($imagenes["imagenUno"], "./images/vehiculos/foto1", $patente, $marca);
        }

        //Manejo la fuente de datos acá:
        public static function TraerVehiculos(){
            $ruta = "./vehiculos.txt";
            
            $listaVehiculos = Archivo::LeerArchivo($ruta);
            if($listaVehiculos == null)
            {
                $listaVehiculos = "Error al traer los datos";
            }
            return $listaVehiculos;
        }

        public static function TraerUnVehiculo($patente){
            $ruta = "./vehiculos.txt";
            
            $listaVehiculos = Vehiculo::TraerVehiculos($ruta);
            $vehiculo = null;
            if($listaVehiculos != null)
            {
            
                foreach($listaVehiculos as $auxVehiculo)
                {
                    if(strcasecmp($auxVehiculo->patente, $patente) == 0)
                    {
                        $vehiculo = $auxVehiculo;
                        break;
                    }
                }
            
            }
            return $vehiculo;
        }
        
        public static function GuardarVehiculo($vehiculo){
            $ruta = "./vehiculos.txt";

            $vehiculoRepetido = false;
            
            $guardo = false;

            //Veo si está repetido
            if(file_exists($ruta))
            {
                $listavehiculos = Vehiculo::TraerVehiculos();
                
                foreach($listavehiculos as $auxVehiculo)
                {
                    if(strtolower($auxVehiculo->patente) == strtolower($vehiculo->patente))
                    {
                        $vehiculoRepetido = true;
                        break;
                    }
                }
            }
                
            //guardo
            if($vehiculoRepetido == false)
            {
                Archivo::GuardarUno($ruta, $vehiculo);
                $guardo = true;
            }
            
            return $guardo;
        } 
    
        public static function ValidarPatente($patente){
            $validado = true;
            $listaVehiculos = Vehiculo::TraerVehiculos();
            if(is_array($listaVehiculos))
            {
                foreach($listaVehiculos as $auxVehiculo)
                {
                    if(strcasecmp($patente, $auxVehiculo->patente) == 0)
                    {
                        $validado = false;
                        break;
                    }
                }
            }
            return $validado;
        }

        public static function ModificarVehiculo($elementoModificado){
            $ruta = "./vehiculos.txt";
            $listaVehiculos = Vehiculo::TraerVehiculos();

            for($i= 0 ; $i < count($listaVehiculos); $i++)
            {
                $vehiculoAux = $listaVehiculos[$i];
                //Modifico
                if(strcasecmp($vehiculoAux->patente, $elementoModificado->patente))
                {
                    $extension = pathinfo($vehiculoAux->rutaFoto, PATHINFO_EXTENSION);
                    $nombreBackup = "./backupFotos/backup" . $elementoModificado->patente . "." . $extension;
                    //guardo la foto en la carpeta de backup:
                    copy($vehiculoAux->rutaFoto, $nombreBackup);
                    unlink($vehiculoAux->rutaFoto);
                    //reemplazo
                    $listaVehiculos[$i] = $elementoModificado;
                    Archivo::GuardarTodos($ruta, $listaVehiculos);
                    break;
                }
            }
        }
        // public static function BorrarVehiculo($patente)
        // {
        //     $lista = Archivo::LeerArchivo($ruta);
        //     if($lista != null)
        //     {
        //         if(count($lista) > 1)
        //         {
        //             for($i = 0; $i < count($lista); $i++)
        //             {
        //                 $objeto = $lista[$i];
        //                 if($objeto->legajo == $nroLegajo)
        //                 {
        //                     unlink($lista[$i]->rutaFoto);
        //                     unset($lista[$i]);//elimino elemento de la lista
        //                     array_values($lista); //indices correlativos
        //                     break;
        //                 }
        //             }
        //             //guardo los datos de nuevo en el archivo                 
        //             Archivo::GuardarTodos($lista);
        //         }
        //         else if($lista[0]->legajo == $nroLegajo)
        //         {
        //             unlink($lista[$i]->rutaFoto);
        //             unlink($ruta);
                    
        //         }
        //     }
        // }

        public static function CrearTabla($listaTurnos){
            $tablaTurnos = "<table>
                            <thead>
                                <tr>
                                    <th>Marca</th>
                                    <th>Patente</th>
                                    <th>KMS</th>
                                    <th>foto</th>
                                </tr>     
                            </thead>
                            <tbody>";
            
            foreach($listaTurnos as $turno)
            {
                $tablaTurnos .= "<tr>
                                    <td>" . $turno->marca . "</td>
                                    <td>" . $turno->patente . "</td>
                                    <td>" . $turno->kms . "</td>
                                    <td>" . $turno->rutaFoto . "</td>
                                </tr>";
            }
                                    
            $tablaTurnos .=  "</tbody></table>";
            return $tablaTurnos;
        }
    }
    
?>