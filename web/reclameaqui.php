<?php

$client = new \GuzzleHttp\Client ([
    'defaults' => [ 'verify' => FALSE ]
]);

$cache = array ();

$ra = array ();

foreach ($bovespa as $ticker => $info)
{
    /*
    if (trim ($info->URL) == '')
        continue;
    
    if (array_key_exists ($info->URL, $cache))
    {
        $ra [$ticker] = $cache [$info->URL];

        continue;
    }
    
    echo $info->URL;
    
    $request = $client->post (URL_RA1, [
        'form_params' => [
            'url' => $info->URL
        ]
    ]);

    $obj = json_decode ((string) $request->getBody ());

    if (is_object ($obj) && isset ($obj->reputacao) && is_object ($obj->reputacao))
    {
        $ra [$ticker] = $cache [$info->URL] = $obj->reputacao;

        continue;
    }
    */

    $name = trim ($info->DENOM_COMERC) == '' ? $info->DENOM_SOCIAL : $info->DENOM_COMERC;

    $name = trim (str_replace (array ('SA', 'S.A', 'S/A', 'S.A.'), '', $name));

    if (array_key_exists ($name, $cache))
    {
        $ra [$ticker] = $cache [$name];

        continue;
    }

    $request = $client->get (URL_RA2 . urlencode ($name));

    $search = json_decode ((string) $request->getBody ());

    if (!sizeof ($search->companies))
        continue;

    $request = $client->get (URL_RA3 . $search->companies [0]->id .'/compare');

    $obj = json_decode ((string) $request->getBody ());

    foreach ($obj->indexes as $trash => $index)
        if ($index->type == 'TWELVE_MONTHS')
            break;
    
    unset ($index->company);

    $ra [$ticker] = $cache [$name] = $index;
}

unset ($cache);

return $ra;
