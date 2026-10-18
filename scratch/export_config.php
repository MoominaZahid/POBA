<?php

$data = json_decode(file_get_contents('scratch/countries_data.json'), true);

$export = "<?php\n\nreturn " . var_export($data, true) . ";\n";
file_put_contents('config/countries_phone.php', $export);
echo "Written config/countries_phone.php with " . count($data) . " countries.\n";
