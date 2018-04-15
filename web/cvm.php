<?php

$array = file (URL_CVM);

$attributes = str_getcsv (utf8_encode ($array [0]), ';');

$cvm = array ();

for ($i = 1; $i < sizeof ($array); $i++)
{
    $obj = array ();

    $aux = str_getcsv (utf8_encode ($array [$i]), ';');

    for ($j = 0; $j < sizeof ($attributes); $j++)
        $obj [$attributes [$j]] = $aux [$j];
    
    $cvm [] = (Object) $obj;
}

return $cvm;
