<?php

$html = utf8_encode (file_get_contents (URL_ALL));

preg_match_all ('"detalhes\.php\?papel=([A-Z0-9]+)"', $html, $out);

return $out [1];
