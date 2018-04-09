<?php

define ('URL_ALL', 'http://www.fundamentus.com.br/resultado.php');
define ('URL_STK', 'http://www.fundamentus.com.br/detalhes.php?papel=');

require ('../vendor/autoload.php');

$tickers = require ('all.php');

$stocks = require ('stocks.php');

echo json_encode ($stocks);
