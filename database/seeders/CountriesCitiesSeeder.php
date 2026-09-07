<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesCitiesSeeder extends Seeder
{
    /**
     * Source: dr5hn/countries-states-cities-database (json/countries+cities.json)
     */
    public function run(): void
    {
        $path = database_path('data/countries_cities.json');
        if (!file_exists($path)) {
            $this->command->error("Data file not found: $path");
            return;
        }

        $countries = json_decode(file_get_contents($path), true);

        DB::table('cities')->delete();
        DB::table('countries')->delete();

        $now = now();
        $countryRows = [];
        foreach ($countries as $country) {
            $countryRows[] = ['name' => $country['name'], 'created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($countryRows, 500) as $chunk) {
            DB::table('countries')->insert($chunk);
        }

        $countryIds = DB::table('countries')->pluck('id', 'name');

        $cityRows = [];
        foreach ($countries as $country) {
            $countryId = $countryIds[$country['name']] ?? null;
            if (!$countryId || empty($country['cities'])) continue;

            foreach ($country['cities'] as $cityName) {
                $cityRows[] = [
                    'name' => $cityName,
                    'country_id' => $countryId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($cityRows) >= 2000) {
                    DB::table('cities')->insert($cityRows);
                    $cityRows = [];
                }
            }
        }
        if (!empty($cityRows)) {
            DB::table('cities')->insert($cityRows);
        }

        $this->command->info('Seeded ' . count($countryRows) . ' countries and ' . DB::table('cities')->count() . ' cities.');
    }
}
