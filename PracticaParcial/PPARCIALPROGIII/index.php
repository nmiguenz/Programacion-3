<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'clases/alumno.php';

$config ['displayErrorDetails']=true;
$config ['addContentLengthHeader']= false;

$app=new \Slim\App(["settings"=> $config]);

// 1- (2 pt.) caso: cargarAlumno (post): Se deben guardar los siguientes datos: nombre, apellido, email y foto. Los
// datos se guardan en el archivo de texto alumnos.txt, tomando el email como identificador.
$app->post('/alumno/cargar',function (Request $request, Response $response){
    $arrayDeParametros = $request->getParsedBody();
    $foto = $_FILES["foto"];     
    $objeto = new Alumno();
    $objeto->cargarAlumno($arrayDeParametros, $foto);
    $newResponse = $response->withJson($objeto,200);
    return $newResponse;
});


// 2- (2pt.) caso: consultarAlumno (get): Se ingresa apellido, si coincide con algún registro del archivo alumno.txt se
// retorna todos los alumnos con dicho apellido, si no coincide se debe retornar “No existe alumno con apellido
// xxx” (xxx es el apellido que se busco) La búsqueda tiene que ser case insensitive.



// 3- (1 pts.) caso: cargarMateria (post): Se recibe el nombre de la materia, código de materia, el cupo de alumnos y
// el aula donde se dicta y se guardan los datos en el archivo materias.txt, tomando como identificador el código de
// la materia.
// 4- (2pts.) caso: inscribirAlumno (get): Se recibe nombre, apellido, mail del alumno, materia y código de la materia
// y se guarda en el archivo inscripciones.txt restando un cupo a la materia en el archivo materias.txt. Si no hay
// cupo o la materia no existe informar cada caso particular.
// 5- (1pt.) caso: inscripciones(get): Se devuelve un tabla con todos los alumnos inscriptos a todas las materias.
// 6- (2pts.) caso: inscripciones(get): Puede recibir el parámetro materia o apellido y filtra la tabla de acuerdo al
// parámetro pasado.

$app->run();

?>