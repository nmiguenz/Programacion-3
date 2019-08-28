<?php
echo "Ejercicio 1" ;

    $nombre = "Nicolas";
    $apellido = "Miguenz";
    $concatenacion = '<p>Su nombre y apellido es: '.$nombre. ', '.$apellido.'</p>';

    echo $concatenacion ;


echo "Ejercicio 2";
    $x = -3;
    $y = 15;
    $suma = $x + $y;

    print("<br>El resultado de la suma es: $suma</br>");

echo "<br>Ejercicio 3";
    print("<br>El valor de la variable X es $x</br>");
    print("El valor de la variable Y es $y</br>");
    print("El resultado de la suma es: $suma</br>");

echo "<br>Ejercicio 4";
    $i = 1;
    $suma = 0;
    $contador = 0;
    while ($suma < 1000)
    {
        $suma+=$i;
        echo "<br>$i";
        $i++;
        $contador ++;
    }
    print("<br>la cantidad de numeros iterados es: $contador</br>"); 

echo "<br>Ejercicio 5";
    $a = 7;
    $b = 3;
    $c = 5;

    if ($a>$b && $a<$c || $a<$b && $a>$c )
        print("El numero intermedio es $a");
    else if($b>$a && $b<$c || $b<$a && $b>$c)
        print("El numero intermedio es $b");
    else if($c>$a && $c<$b || $c<$a && $c>$b)
        print("El numero intermedio es $c");
    else
        print("no hay numero intermedio");

?>