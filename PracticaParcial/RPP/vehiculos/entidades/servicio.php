<?php
    class Servicio
    {
        public $id;
        public $tipo;
        public $precio;
        public $demora;
        
        public function __construct($id, $tipo, $precio, $demora)
        {
            $this->id = $id;
            $this->tipo = $tipo;
            $this->precio = $precio;
            $this->demora = $demora;
        }

        //Manejo la fuente de datos acá:
        public static function TraerServicios(){
            $ruta = "./tipoServicio.txt";
            
            $listaServicios = Archivo::LeerArchivo($ruta);
            if($listaServicios == null)
            {
                $listaServicios = "Error al traer los datos";
            }
            return $listaServicios;
        }

        public static function TraerUnServicio($id){
            $ruta = "./tipoServicio.txt";
            
            $listaServicios = Servicio::TraerServicios($ruta);
            $servicio = null;
            if($listaServicios != null)
            {
            
                foreach($listaServicios as $auxServicio)
                {
                    if($auxServicio->id == $id)
                    {
                        $servicio = $auxServicio;
                        break;
                    }
                }
            
            }
            return $servicio;
        }

        public static function GuardarServicio($servicio){
            $ruta = "./tipoServicio.txt";

            $servicioRepetido = false;
            
            $guardo = false;
            //Veo si está repetido
            if(file_exists($ruta))
            {
                $listaServicios = Servicio::TraerServicios();
                
                if(is_array($listaServicios))
                {
                    foreach($listaServicios as $auxServicio)
                    {
                        if($auxServicio->id == $servicio->id)
                        {
                            $servicioRepetido = true;
                            break;
                        }
                    }
                }
            }
                
            //guardo
            if($servicioRepetido == false)
            {
                Archivo::GuardarUno($ruta, $servicio);
                $guardo = true;
            }
            
            return $guardo;
        } 
    
        public static function ValidarTipo($servicio){
            
            $validado = false;
            
            if(strcasecmp($servicio, "10000") == 0 || strcasecmp($servicio, "20000") == 0
            || strcasecmp($servicio, "50000") == 0)
            {
                $validado = true;
            }
            return $validado;
        }

        public static function ValidarId($id){
            $validado = true;
            $listaServicios = Servicio::TraerServicios();
            if(is_array($listaServicios))
            {
                foreach($listaServicios as $auxServicio)
                {
                    if($auxServicio->id == $id)
                    {
                        $validado = false;
                        break;
                    }
                }
            }
            return $validado;
        }



        public static function ModificarVehiculo($elementoModificado)
        {
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
    }
    
?>