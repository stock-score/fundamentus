<?php

$companies = array ();

foreach ($cvm as $trash => $enterprise)
{
    if ($enterprise->SIT != 'ATIVO')
        continue;

    $html = file_get_contents (URL_BMF . $enterprise->CD_CVM);

    $html = str_replace (array ("\r", "\n", "\t"), '', $html);

    $html = preg_replace ('/(\>)\s*(\<)/m', '$1$2', $html);

    preg_match_all ("#var symbols = '([A-Z0-9|]+)';#u", $html, $out1);

    if (trim ($out1 [1][0]) == '')
        continue;
    
    $tcks = explode ('|', $out1 [1][0]);

    preg_match_all ('#<tr><td>Site:</td><td><a target="_blank" href="([^"]+)">[^<]+</a></td></tr>#u', $html, $out2);

    $url = str_replace ('http://http://', 'http://', $out2 [1][0]);

    // $url = str_replace ('//ri.', '//', $url);

    $parse = parse_url ($url);

    $enterprise->URL = $parse ['scheme'] .'://'. $parse ['host'];

    foreach ($tcks as $trash => $t)
        $companies [$t] = $enterprise;
}

return $companies;
