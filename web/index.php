<?php

define ('URL_ALL', 'http://www.fundamentus.com.br/resultado.php');
define ('URL_STK', 'http://www.fundamentus.com.br/detalhes.php?papel=');
define ('URL_CVM', 'http://dados.cvm.gov.br/dados/CIA_ABERTA/CAD/DADOS/inf_cadastral_cia_aberta.csv');
define ('URL_BMF', 'http://bvmf.bmfbovespa.com.br/pt-br/mercados/acoes/empresas/ExecutaAcaoConsultaInfoEmp.asp?CodCVM=');
define ('URL_RA1', 'http://app02.reclameaqui.com.br/reputacao');
define ('URL_RA2', 'https://iosearch.reclameaqui.com.br/raichu-io-site-search-0.0.1-SNAPSHOT/companies/search/');
define ('URL_RA3', 'https://iosite.reclameaqui.com.br/raichu-io-site-0.0.1-SNAPSHOT/company/');

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
    $tickers = require 'all.php';

    $stocks = require 'stocks.php';

    $cvm = require 'cvm.php';

    $bovespa = require 'bovespa.php';

    $ra = require 'reclameaqui.php';

    $final = array ();

    foreach ($stocks as $trash => $stock)
    {
        $aux = array (
            'fundamentus' => $stock,
            'cvm' => $bovespa [$stock->ticker],
            'reclameaqui' => $ra [$stock->ticker]
        );

        $final [$stock->ticker] = (Object) $aux;
    }

    $json = json_encode ($final);

    $result = $client->putObject([
        'Bucket' => $bucket,
        'Key'    => $file,
        'Body'   => $json
    ]);
}

echo $json;
