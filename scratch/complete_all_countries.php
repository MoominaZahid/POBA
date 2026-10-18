<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = json_decode(file_get_contents('scratch/countries_data.json'), true);
$dbCountries = App\Models\Country::pluck('name')->toArray();

$extra = [
    'Antarctica' => ['code' => '+672', 'iso' => 'aq', 'flag' => '🇦🇶', 'max' => 6, 'ph' => '123 456'],
    'Bonaire, Sint Eustatius and Saba' => ['code' => '+599', 'iso' => 'bq', 'flag' => '🇧🇶', 'max' => 7, 'ph' => '712 3456'],
    'Bouvet Island' => ['code' => '+47', 'iso' => 'bv', 'flag' => '🇧🇻', 'max' => 8, 'ph' => '412 34 567'],
    'British Indian Ocean Territory' => ['code' => '+246', 'iso' => 'io', 'flag' => '🇮🇴', 'max' => 7, 'ph' => '370 1234'],
    'Christmas Island' => ['code' => '+61', 'iso' => 'cx', 'flag' => '🇨🇽', 'max' => 9, 'ph' => '412 345 678'],
    'Cocos (Keeling) Islands' => ['code' => '+61', 'iso' => 'cc', 'flag' => '🇨🇨', 'max' => 9, 'ph' => '412 345 678'],
    'Cook Islands' => ['code' => '+682', 'iso' => 'ck', 'flag' => '🇨🇰', 'max' => 5, 'ph' => '21 234'],
    'Curaçao' => ['code' => '+599', 'iso' => 'cw', 'flag' => '🇨🇼', 'max' => 7, 'ph' => '9 123 4567'],
    'Falkland Islands' => ['code' => '+500', 'iso' => 'fk', 'flag' => '🇫🇰', 'max' => 5, 'ph' => '51 234'],
    'Faroe Islands' => ['code' => '+298', 'iso' => 'fo', 'flag' => '🇫🇴', 'max' => 6, 'ph' => '212 345'],
    'French Guiana' => ['code' => '+594', 'iso' => 'gf', 'flag' => '🇬🇫', 'max' => 9, 'ph' => '694 12 34 56'],
    'French Polynesia' => ['code' => '+689', 'iso' => 'pf', 'flag' => '🇵🇫', 'max' => 8, 'ph' => '87 12 34 56'],
    'French Southern Territories' => ['code' => '+262', 'iso' => 'tf', 'flag' => '🇹🇫', 'max' => 9, 'ph' => '692 12 34 56'],
    'Gibraltar' => ['code' => '+350', 'iso' => 'gi', 'flag' => '🇬🇮', 'max' => 8, 'ph' => '5712 3456'],
    'Greenland' => ['code' => '+299', 'iso' => 'gl', 'flag' => '🇬🇱', 'max' => 6, 'ph' => '21 23 45'],
    'Grenada' => ['code' => '+1473', 'iso' => 'gd', 'flag' => '🇬🇩', 'max' => 7, 'ph' => '473 1234'],
    'Guadeloupe' => ['code' => '+590', 'iso' => 'gp', 'flag' => '🇬🇵', 'max' => 9, 'ph' => '690 12 34 56'],
    'Guam' => ['code' => '+1671', 'iso' => 'gu', 'flag' => '🇬🇺', 'max' => 7, 'ph' => '671 1234'],
    'Guernsey' => ['code' => '+44', 'iso' => 'gg', 'flag' => '🇬🇬', 'max' => 10, 'ph' => '7911 123456'],
    'Guinea-Bissau' => ['code' => '+245', 'iso' => 'gw', 'flag' => '🇬🇼', 'max' => 7, 'ph' => '955 1234'],
    'Heard Island and McDonald Islands' => ['code' => '+672', 'iso' => 'hm', 'flag' => '🇭🇲', 'max' => 6, 'ph' => '123 456'],
    'Jersey' => ['code' => '+44', 'iso' => 'je', 'flag' => '🇯🇪', 'max' => 10, 'ph' => '7911 123456'],
    'Kiribati' => ['code' => '+686', 'iso' => 'ki', 'flag' => '🇰🇮', 'max' => 5, 'ph' => '72 123'],
    'Kosovo' => ['code' => '+383', 'iso' => 'xk', 'flag' => '🇽🇰', 'max' => 8, 'ph' => '44 123 456'],
    'Liechtenstein' => ['code' => '+423', 'iso' => 'li', 'flag' => '🇱🇮', 'max' => 7, 'ph' => '79 123 45'],
    'Macau S.A.R.' => ['code' => '+853', 'iso' => 'mo', 'flag' => '🇲🇴', 'max' => 8, 'ph' => '6612 3456'],
    'Man (Isle of)' => ['code' => '+44', 'iso' => 'im', 'flag' => '🇮🇲', 'max' => 10, 'ph' => '7911 123456'],
    'Marshall Islands' => ['code' => '+692', 'iso' => 'mh', 'flag' => '🇲🇭', 'max' => 7, 'ph' => '235 1234'],
    'Martinique' => ['code' => '+596', 'iso' => 'mq', 'flag' => '🇲🇶', 'max' => 9, 'ph' => '696 12 34 56'],
    'Mayotte' => ['code' => '+262', 'iso' => 'yt', 'flag' => '🇾🇹', 'max' => 9, 'ph' => '639 12 34 56'],
    'Micronesia' => ['code' => '+691', 'iso' => 'fm', 'flag' => '🇫🇲', 'max' => 7, 'ph' => '920 1234'],
    'Montserrat' => ['code' => '+1664', 'iso' => 'ms', 'flag' => '🇲🇸', 'max' => 7, 'ph' => '664 1234'],
    'Nauru' => ['code' => '+674', 'iso' => 'nr', 'flag' => '🇳🇷', 'max' => 7, 'ph' => '557 1234'],
    'New Caledonia' => ['code' => '+687', 'iso' => 'nc', 'flag' => '🇳🇨', 'max' => 6, 'ph' => '75 12 34'],
    'Niue' => ['code' => '+683', 'iso' => 'nu', 'flag' => '🇳🇺', 'max' => 4, 'ph' => '4123'],
    'Norfolk Island' => ['code' => '+672', 'iso' => 'nf', 'flag' => '🇳🇫', 'max' => 6, 'ph' => '322 123'],
    'North Korea' => ['code' => '+850', 'iso' => 'kp', 'flag' => '🇰🇵', 'max' => 10, 'ph' => '191 234 5678'],
    'Northern Mariana Islands' => ['code' => '+1670', 'iso' => 'mp', 'flag' => '🇲🇵', 'max' => 7, 'ph' => '670 1234'],
    'Palau' => ['code' => '+680', 'iso' => 'pw', 'flag' => '🇵🇼', 'max' => 7, 'ph' => '775 1234'],
    'Pitcairn Island' => ['code' => '+64', 'iso' => 'pn', 'flag' => '🇵🇳', 'max' => 9, 'ph' => '21 123 4567'],
    'Reunion' => ['code' => '+262', 'iso' => 're', 'flag' => '🇷🇪', 'max' => 9, 'ph' => '692 12 34 56'],
    'Saint Helena' => ['code' => '+290', 'iso' => 'sh', 'flag' => '🇸🇭', 'max' => 4, 'ph' => '5123'],
    'Saint Kitts and Nevis' => ['code' => '+1869', 'iso' => 'kn', 'flag' => '🇰🇳', 'max' => 7, 'ph' => '869 1234'],
    'Saint Lucia' => ['code' => '+1758', 'iso' => 'lc', 'flag' => '🇱🇨', 'max' => 7, 'ph' => '758 1234'],
    'Saint Pierre and Miquelon' => ['code' => '+508', 'iso' => 'pm', 'flag' => '🇵🇲', 'max' => 6, 'ph' => '55 12 34'],
    'Saint Vincent and the Grenadines' => ['code' => '+1784', 'iso' => 'vc', 'flag' => '🇻🇨', 'max' => 7, 'ph' => '784 1234'],
    'Saint-Barthelemy' => ['code' => '+590', 'iso' => 'bl', 'flag' => '🇧🇱', 'max' => 9, 'ph' => '690 12 34 56'],
    'Saint-Martin (French part)' => ['code' => '+590', 'iso' => 'mf', 'flag' => '🇲🇫', 'max' => 9, 'ph' => '690 12 34 56'],
    'Samoa' => ['code' => '+685', 'iso' => 'ws', 'flag' => '🇼🇸', 'max' => 7, 'ph' => '72 12345'],
    'San Marino' => ['code' => '+378', 'iso' => 'sm', 'flag' => '🇸🇲', 'max' => 10, 'ph' => '66 123 456'],
    'Sao Tome and Principe' => ['code' => '+239', 'iso' => 'st', 'flag' => '🇸🇹', 'max' => 7, 'ph' => '981 1234'],
    'Sint Maarten (Dutch part)' => ['code' => '+1721', 'iso' => 'sx', 'flag' => '🇸🇽', 'max' => 7, 'ph' => '721 1234'],
    'Solomon Islands' => ['code' => '+677', 'iso' => 'sb', 'flag' => '🇸🇧', 'max' => 7, 'ph' => '741 2345'],
    'South Georgia' => ['code' => '+500', 'iso' => 'gs', 'flag' => '🇬🇸', 'max' => 5, 'ph' => '51 234'],
    'Suriname' => ['code' => '+597', 'iso' => 'sr', 'flag' => '🇸🇷', 'max' => 7, 'ph' => '712 3456'],
    'Svalbard and Jan Mayen Islands' => ['code' => '+47', 'iso' => 'sj', 'flag' => '🇸🇯', 'max' => 8, 'ph' => '412 34 567'],
    'Timor-Leste' => ['code' => '+670', 'iso' => 'tl', 'flag' => '🇹🇱', 'max' => 8, 'ph' => '7712 3456'],
    'Tokelau' => ['code' => '+690', 'iso' => 'tk', 'flag' => '🇹🇰', 'max' => 4, 'ph' => '4123'],
    'Tonga' => ['code' => '+676', 'iso' => 'to', 'flag' => '🇹🇴', 'max' => 5, 'ph' => '71 234'],
    'Turks and Caicos Islands' => ['code' => '+1649', 'iso' => 'tc', 'flag' => '🇹🇨', 'max' => 7, 'ph' => '649 1234'],
    'Tuvalu' => ['code' => '+688', 'iso' => 'tv', 'flag' => '🇹🇻', 'max' => 5, 'ph' => '20 123'],
    'United States Minor Outlying Islands' => ['code' => '+1', 'iso' => 'us', 'flag' => '🇺🇸', 'max' => 10, 'ph' => '202 555 0123'],
    'Vanuatu' => ['code' => '+678', 'iso' => 'vu', 'flag' => '🇻🇺', 'max' => 7, 'ph' => '771 2345'],
    'Vatican City State (Holy See)' => ['code' => '+379', 'iso' => 'va', 'flag' => '🇻🇦', 'max' => 10, 'ph' => '06 698 12345'],
    'Virgin Islands (British)' => ['code' => '+1284', 'iso' => 'vg', 'flag' => '🇻🇬', 'max' => 7, 'ph' => '284 1234'],
    'Virgin Islands (US)' => ['code' => '+1340', 'iso' => 'vi', 'flag' => '🇻🇮', 'max' => 7, 'ph' => '340 1234'],
    'Wallis and Futuna Islands' => ['code' => '+681', 'iso' => 'wf', 'flag' => '🇼🇫', 'max' => 6, 'ph' => '72 12 34'],
    'Western Sahara' => ['code' => '+212', 'iso' => 'eh', 'flag' => '🇪🇭', 'max' => 9, 'ph' => '612 345678'],
];

foreach ($extra as $name => $info) {
    $data[$name] = $info;
}

// Download missing flags if any
$mh = curl_multi_init();
$handles = [];
foreach ($extra as $name => $info) {
    $iso = $info['iso'];
    $file = "public/images/flags/{$iso}.png";
    if (!file_exists($file)) {
        $ch = curl_init("https://flagcdn.com/w40/{$iso}.png");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_multi_add_handle($mh, $ch);
        $handles[$iso] = $ch;
    }
}
if (count($handles) > 0) {
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);
    foreach ($handles as $iso => $ch) {
        $content = curl_multi_getcontent($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 && strlen($content) > 50) {
            file_put_contents("public/images/flags/{$iso}.png", $content);
        }
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
}
curl_multi_close($mh);

file_put_contents('scratch/countries_data.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Total mapped: " . count($data) . "\n";
$missing = 0;
foreach ($dbCountries as $c) {
    if (!isset($data[$c])) $missing++;
}
echo "Missing in DB: " . $missing . "\n";
