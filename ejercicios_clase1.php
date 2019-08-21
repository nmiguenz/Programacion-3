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
        $a = 5;
        $b = 10;
        $c = 7;

        if ($a<$b)
        {
            if($b<$c)
            {
                print("<br>el valor medio es: $b</br>"); 
            }
            else{
                print("<br>el valor medio es: $c</br>");
            }
        }
        else if ($b<$a)
        {
            if($a<$c)
            {
                print("<br>el valor medio es: $a</br>");
            }
            else
            {
                print("<br>el valor medio es: $c</br>"); 
            }
        }
        else if ($c<$a)
        {
            if($a<$c)
            {
                print("<br>el valor medio es: $a</br>");
            }
            else
            {
                print("<br>el valor medio es: $c</br>"); 
            }
        }

?>