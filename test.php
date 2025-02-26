<?php
# Comment 1
// Comment 2

$s1 = 'Hello World';

echo ('<h1>index$s1<h1>'); //string
echo ("<h4>Home$s1<h4>"); //render value var into it or compiled

$s2 = 'Hello World';

$s3 = $s1 . ' ' . $s2;
echo ("<h1>$s3<h1>");
echo ('<h1>' . $s3 . '</h1>');
echo ('<h1>' . $s3 . '</h1>');

$n1 = 123; //integer
echo ('<h1>index$n1<h1>'); //string
echo ("<h4>Home$n1<h4>"); //render value var into it or compiled

$n1 = 123.45; //double //float

$bol1 = true; //boolean

print($s1);
echo ('<br>');
printf($s2);
echo ('<br>');

$array = ['A', 'B', 'C'];
print_r($array);
echo ('<br>');

$array = ['A1', 'B2', 'C3', 'D41'];
var_dump($array);
