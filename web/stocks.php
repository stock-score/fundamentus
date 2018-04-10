<?php

unset ($html);
unset ($out);

// Debug
// $tickers = array ('ABEV3');

$stocks = array ();

foreach ($tickers as $trash => $ticker)
{
    $html = utf8_encode (file_get_contents (URL_STK . $ticker));

    // Getting labels of attributes and its description...
    // <span class="help tips" title="Código da ação">?</span><span class="txt">Papel</span>
    /*
    preg_match_all ('/<span class="help tips" title="([^"]+)">\?<\/span><span class="txt">([^<]+)<\/span>/', $html, $out);
    
    $tips = array ();
    
    foreach ($out [0] as $key => $trash)
        $tips [] = (Object) array ('attribute' => $out [2][$key], 'tip' => $out [1][$key]);
    
    echo json_encode ($tips);
    */
    
    $html = str_replace (array ("\r", "\n", "\t"), '', $html);

    $html = preg_replace ('/(\>)\s*(\<)/m', '$1$2', $html);

    preg_match_all ('#</span><span class="txt">([^<]+)</span></td><td class="data[^"]*"><span class="txt">([^<]+)</span>#u', $html, $out1);

    preg_match_all ('#</span><span class="txt">([^<]+)</span></td><td class="data[^"]*"><span class="txt"><a href="[^"]+">([^<]+)</a></span>#u', $html, $out2);

    preg_match_all ('#<span class="txt">([^<]+)</span></td><td class="data[^"]*"><span class="oscil"><font color="[^"]+">([^<]+)</font></span>#u', $html, $out3);

    // Debug
    /*
    print_r ($out1);
    print_r ($out2);
    print_r ($out3);
    die ();
    */

    $attributes = array ('ticker' => $ticker);
    

    foreach ($out1 [0] as $key => $trash)
        if (!in_array ($out1 [1][$key], array ('Receita Líquida', 'EBIT', 'Lucro Líquido')))
            $attributes [$out1 [1][$key]] = trim (strip_tags ($out1 [2][$key]));
        else
        {
            $aux = array_key_exists ($out1 [1][$key] .' 12 meses', $attributes) ? $out1 [1][$key] .' 3 meses' : $out1 [1][$key] .' 12 meses';

            $attributes [$aux] = trim (strip_tags ($out1 [2][$key]));
        }
    
    foreach ($out2 [0] as $key => $trash)
        $attributes [$out2 [1][$key]] = trim (strip_tags ($out2 [2][$key]));
    
    foreach ($out3 [0] as $key => $trash)
        $attributes ['Oscilações - '. $out3 [1][$key]] = trim (strip_tags ($out3 [2][$key]));
    
    $stocks [] = (Object) $attributes;
}

return $stocks;
