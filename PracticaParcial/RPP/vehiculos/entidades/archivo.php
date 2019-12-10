<?php
    class Archivo
    {
        public static function GuardarTodos($ruta, $lista)
        {
            $guardo = false;
            
            $archivo = fopen($ruta, "w");
            foreach($lista as $objeto)
            {
                fwrite($archivo, json_encode($objeto) . PHP_EOL);
            }
            fclose($archivo);
            if(file_exists($ruta))
            {
                $guardo = true;
            }
            return $guardo;
        }

        public static function GuardarUno($ruta, $dato)
        {
            $guardo = false;
            
            $archivo = fopen($ruta, "a");
            fwrite($archivo, json_encode($dato) . PHP_EOL);
            fclose($archivo);
            if(file_exists($ruta))
            {
                $guardo = true;
            }
            return $guardo;
        
        }
      
        // public static function GuardarArchivoTemporal($archivo, $destino, $tipo, $sabor)
        // {
        //     $origen = $archivo->getClientFileName();
            
        //     $fecha = new DateTime();
        //     $fecha = $fecha->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        //     $fecha = $fecha->format("d-m-Y-Hi");
        //     $extension = pathinfo($archivo->getClientFileName(), PATHINFO_EXTENSION);
        //     $destino = "$destino-$tipo-$sabor-$fecha.$extension";
        //     $archivo->moveTo($destino);
        //     return $destino;
        // }

        //Devuelve un ARRAY
        public static function LeerArchivo($ruta)
        {
            $lista = array();
            if(file_exists($ruta))
            {             
                $archivo = fopen($ruta, "r");           
                while(!feof($archivo))
                {
                    $objeto = json_decode(fgets($archivo));
                    if($objeto != null)
                    {
                        array_push($lista, $objeto);
                    }
                }
                
                fclose($archivo);        
            }
            return $lista;
        }

        public static function GuardarArchivoTemporal($archivo, $destino)
        {
          
            $origen = $archivo["tmp_name"];
            $fecha = new DateTime();
            $extension = pathinfo($_FILES["archivo"]["name"], PATHINFO_EXTENSION);
            $destino = $destino . "archivo" . $fecha->getTimeStamp() . "." . $extension;
            move_uploaded_file($origen, $destino);

            return $destino;
        }
    
    }
?>