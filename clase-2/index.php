<?php
 session_start();
 include_once './clases/alumno.php';
 include_once './clases/funciones.php';
    
    //var_dump($_GET);
    //echo $_GET['nombre'];
    //isset($_GET['nombre']);

    /*
    $alumno = new Alumno($_GET["nombre"], $_GET['apellido']);
    $datos = $alumno->saludar();
    echo $datos;
    */
    /*
    $cantidad = $_POST['cantidad'];
    $i = 0;
    while($i < $cantidad){

        $alumno = new Alumno($_POST['nombre'],$_POST['apellido']);
        $i = $i+1; 
        $datos = $alumno->saludar();
        echo $datos;
    }
    */
    
    if(!isset($_SESSION['SesionAlumno'])){
        $_SESSION['SesionAlumno'] = array() ;
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $funciones = new Funciones();
        $funciones->traerListado();
       
    }
    else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $funciones = new Funciones();
        $funciones->guardarLista();
    }

?>
   