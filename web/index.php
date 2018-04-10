<?php

define ('URL_ALL', 'http://www.fundamentus.com.br/resultado.php');
define ('URL_STK', 'http://www.fundamentus.com.br/detalhes.php?papel=');

require ('../vendor/autoload.php');

$sharedConfig = [
    'region'  => 'sa-east-1',
    'version' => 'latest'
];

$sdk = new Aws\Sdk ($sharedConfig);

$client = $sdk->createS3 ();

$bucket = 'stock-score';

$file = 'fundamentus-'. date ('Ymd');

$exists = $client->doesObjectExist ($bucket, $file);

if ($exists)
{
    $result = $client->getObject([
        'Bucket' => 'stock-score',
        'Key'    => $file
    ]);

    $json = $result ['Body'];
}
else
{
    $tickers = require ('all.php');

    $stocks = require ('stocks.php');

    $json = json_encode ($stocks);

    $result = $client->putObject([
        'Bucket' => $bucket,
        'Key'    => $file,
        'Body'   => $json
    ]);
}

echo $json;
