<?php

$data = json_decode(file_get_contents('scratch/countries_data.json'), true);
if (!is_dir('public/images/flags')) {
    mkdir('public/images/flags', 0755, true);
}

$isos = [];
foreach ($data as $country => $info) {
    $isos[$info['iso']] = true;
}

$mh = curl_multi_init();
$handles = [];

foreach (array_keys($isos) as $iso) {
    $targetFile = "public/images/flags/{$iso}.png";
    if (file_exists($targetFile) && filesize($targetFile) > 100) {
        continue;
    }
    $ch = curl_init("https://flagcdn.com/w40/{$iso}.png");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_multi_add_handle($mh, $ch);
    $handles[$iso] = $ch;
}

echo "Downloading " . count($handles) . " flags in parallel...\n";

$running = null;
do {
    curl_multi_exec($mh, $running);
    curl_multi_select($mh);
} while ($running > 0);

$saved = 0;
foreach ($handles as $iso => $ch) {
    $content = curl_multi_getcontent($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code == 200 && strlen($content) > 50) {
        file_put_contents("public/images/flags/{$iso}.png", $content);
        $saved++;
    }
    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
}
curl_multi_close($mh);

echo "Downloaded and saved $saved local flag images.\n";
echo "Total flags in folder: " . count(glob('public/images/flags/*.png')) . "\n";
