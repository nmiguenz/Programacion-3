<?php

include 'funciones/parser.php';

class Alumno
{
    public $nombre;
    public $apellido;
    public $email;
    public $foto;   

    function _construct($nombre, $apellido, $email, $foto)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;    
        $this->email = $email;
        $this->foto = $foto;
    }

    function cargarAlumno($arrayDeParametros,$uploadedFiles){
        $valorRetornado=false;
        $auxArray;
        $arrayDeParametrosDos = leer("alumnos.txt");
        
        //busca el valor en ambos arrays y
        //si lo encuentra devuelve el indice.
        if($arrayDeParametrosDos){
            foreach($arrayDeParametrosDos as $key => $val){
                $auxArray= (array)$val;
                //$valorRetornado=false;
                if($auxArray){
                    if($auxArray['email']===$arrayDeParametros['email']){
                        $valorRetornado=true;
                        break;
                    }
                }   
            }
        }     
        //si no encuentra devuelve false,
        //entonces guardo en el archivo
        if(!$valorRetornado){
            echo ('se guardo el obj: porque no esta repetiro');
            $this->_construct($arrayDeParametros['nombre'],$arrayDeParametros['apellido'],$arrayDeParametros['email'],$uploadedFiles);                
            guardar("alumnos.txt", $this, "a");
        }
        else{
            echo('no se pudo guardar: porque el obj esta repetido');
        }        
    }

    

}

?>