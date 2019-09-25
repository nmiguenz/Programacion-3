<?php

class Alumno
{
    public $nombre;
    public $apellido;
    public $email;
    public $foto;   

    function __construct($nombre, $apellido, $email, $foto)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;    
        $this->email = $email;
        $this->foto = $foto;
    }

    public function cargarAlumno($alumnoJson){  //POST

        $lineaObjeto = [];
        if (file_exists("alumno.txt")) {
            $fileRead = fopen("alumno.txt", "r");
            while (!feof($fileRead)) {
                $linea = fgets($fileRead);
                $lineaObjeto = json_decode($linea);
            }
            fclose($fileRead);

            //ESCRIBO

            $fileWrite = fopen("alumno.txt", "a");
            var_dump($alumnoJson);
            array_push($lineaObjeto, $alumnoJson);
            
            fwrite($fileWrite, $alumnoJson);
            echo "Se agregaron los datos";
            fclose($fileWrite);
        }
        else{
            //Abro escritura : escribo solo el array vacio
            $file = fopen("alumno.txt", "w");
            $emptyArray = [];
            array_push($emptyArray, $alumnoJson);

            $encodedArray = json_encode($emptyArray);
            fwrite($file, $encodedArray);

            echo "Se genero el archivo";
            fclose($file);
        }

    }

    public static function consultarAlumno($apellido)
    {
        $lineaObjeto = [];

        if(file_exists("alumno.txt"))
        {
            $fileRead = fopen("alumno.txt", "r");
            while(!feof($fileRead))
            {
                $linea = fgets($fileRead);
                $lineaObjeto = json_decode($linea);
            }
            fclose($fileRead);

            foreach($lineaObjeto as $arrayObj)
            {
                if($arrayObj->apellido == $apellido)
                {
                    var_dump("Se encontro el apellido ".$apellido);
                }
            }
            
            /*if ($lineaObjeto->apellido == $apellido) {
                var_dump("se encontro el apellido ".$lineaObjeto->apellido);
            }*/
            
        }
        else{
            echo "No se encontro el archivo";
        }
    }
}




?>