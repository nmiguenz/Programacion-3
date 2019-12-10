<?php
class Log
    {
        public $caso;
        public $hora;
        public $ruta;
        
        public function __construct($caso, $ruta)
        {
            $hora = new DateTime();
            $hora = $hora->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));
            $hora = $hora->format("H:i:s");
            $this->caso = $caso;
            $this->hora = $hora;
            $this->ruta = $ruta;           
        }

        public static function GuardarLog($log)
        {
            $ruta = "./info.log";
            
            Archivo::GuardarUno($ruta, $log);
            if(file_exists($ruta))
            {
                $guardo = true;
            }
            return $guardo;
        }

        public static function TraerLogs()
        {
            $ruta = "./info.log";
            
            $listaLogs = Archivo::LeerArchivo($ruta);
            
            return $listaLogs;
        }
    }
?>