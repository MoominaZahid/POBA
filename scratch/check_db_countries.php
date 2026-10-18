<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = json_decode(file_get_contents('scratch/countries_data.json'), true);
$dbCountries = App\Models\Country::pluck('name')->toArray();
$missing = [];
foreach ($dbCountries as $c) {
    if (!isset($data[$c])) {
        $missing[] = $c;
    }
}
echo "Total in DB: " . count($dbCountries) . "\n";
echo "Missing in map: " . count($missing) . "\n";
echo "Missing samples: " . implode(', ', array_slice($missing, 0, 40)) . "\n";
