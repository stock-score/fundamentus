<?php

unset ($html);
unset ($out);

$stocks = array ();

foreach ($tickers as $trash => $ticker)
{
    $html = utf8_encode (file_get_contents (URL_STK . $ticker));

    // die (strip_tags ($html));

    // <span class="help tips" title="Código da ação">?</span><span class="txt">Papel</span>

    // Getting labels of attributes and its description...
    /*
    preg_match_all ('/<span class="help tips" title="([^"]+)">\?<\/span><span class="txt">([^<]+)<\/span>/', $html, $out);
    
    $tips = array ();
    
    foreach ($out [0] as $key => $trash)
        $tips [] = (Object) array ('attribute' => $out [2][$key], 'tip' => $out [1][$key]);
    
    echo json_encode ($tips);
    */

    // <td class="label"><span class="help tips" title="Data do último pregão em  que o ativo foi negociado">?</span><span class="txt">Data últ cot</span></td>
    // <td class="data"><span class="txt">06/04/2018</span></td>
    
    preg_match_all ('/<td class="label"><span class="help tips" title="[^"]+">\?<\/span><span class="txt">([^<]+)<\/span><\/td>[\s]+<td class="data"><span class="txt">([^<]+)<\/span><\/td>/', $html, $out);

    $attributes = array ('ticker' => $ticker);
    
    foreach ($out [0] as $key => $trash)
        $attributes [$out [1][$key]] = trim ($out [2][$key]);
    
    $stocks [] = (Object) $attributes;
}

return $stocks;
