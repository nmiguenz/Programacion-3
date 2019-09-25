<?php
include_once "./Clases/alumno.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["caso"])) {
            switch ($_POST["caso"]) {
                case 'cargarAlumno':
                    echo "Cargo alumno";

                    $nombre;
                    $apellido;
                    $email;
                    $foto;

                    if (isset($_POST["nombre"]) && isset($_POST["apellido"])
                        && isset($_POST["email"]) && isset($_FILES["foto"])) 
                    {
                        $nombre = $_POST["nombre"];
                        $apellido = $_POST["apellido"];
                        $email = $_POST["email"];
                        $foto = $_FILES["foto"];
                        

                        $alumno = new Alumno($nombre, $apellido, $email, $foto);
                        //var_dump($alumno);
                        $alumnoJson = json_encode($alumno);
                        
                        $alumno->cargarAlumno($alumnoJson);
                    }
                    break;
                case 'consultarAlumno':
                    echo "Consulto alumno";
                    $apellido = $_GET["apellido"];
                    Alumno::consultarAlumno($apellido);
                break;

            }
        }
    }
    
?>