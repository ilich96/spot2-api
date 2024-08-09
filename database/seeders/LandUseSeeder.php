<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandUseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('land_uses')->insert([
            [
                'zip_code' => '01120',
                'area_colony_type' => 'A',
                'land_price' => 415694.7294,
                'ground_area' => 159.58,
                'construction_area' => 249.35,
                'subsidy' => 644.2609189922794224,
            ],
            [
                'zip_code' => '01120',
                'area_colony_type' => 'A',
                'land_price' => 194171.4822,
                'ground_area' => 74.54,
                'construction_area' => 106.36,
                'subsidy' => 354.418473733421472,
            ],
            [
                'zip_code' => '01120',
                'area_colony_type' => 'A',
                'land_price' => 81,
                'ground_area' => 150,
                'construction_area' => 249.35,
                'subsidy' => 681.7752693652,
            ],
            [
                'zip_code' => '01120',
                'area_colony_type' => 'C',
                'land_price' => 584754.6864,
                'ground_area' => 224.48,
                'construction_area' => 254.01,
                'subsidy' => 0,
            ],
            [
                'zip_code' => '01120',
                'area_colony_type' => 'A',
                'land_price' => 362085.27,
                'ground_area' => 139,
                'construction_area' => 130,
                'subsidy' => 777.8302463412,
            ],
            [
                'zip_code' => '01408',
                'area_colony_type' => 'E',
                'land_price' => 429813.45,
                'ground_area' => 165,
                'construction_area' => 123,
                'subsidy' => 707.972681122024,
            ],
            [
                'zip_code' => '01408',
                'area_colony_type' => 'E',
                'land_price' => 283937.37,
                'ground_area' => 109,
                'construction_area' => 254,
                'subsidy' => 1159.70169616904,
            ],
            [
                'zip_code' => '01408',
                'area_colony_type' => 'C',
                'land_price' => 489726.84,
                'ground_area' => 188,
                'construction_area' => 132,
                'subsidy' => 831.885779501416,
            ],
            [
                'zip_code' => '01408',
                'area_colony_type' => 'A',
                'land_price' => 513171.21,
                'ground_area' => 197,
                'construction_area' => 372,
                'subsidy' => 0,
            ],
            [
                'zip_code' => '01408',
                'area_colony_type' => 'E',
                'land_price' => 18,
                'ground_area' => 63,
                'construction_area' => 46888.74,
                'subsidy' => 236.571281933328,
            ],
            [
                'zip_code' => '01408',
                'area_colony_type' => 'C',
                'land_price' => 640526.2377,
                'ground_area' => 245.89,
                'construction_area' => 337.74,
                'subsidy' => 849.3762596051186,
            ],
            [
                'zip_code' => '01740',
                'area_colony_type' => 'A',
                'land_price' => 418976.9412,
                'ground_area' => 160.84,
                'construction_area' => 162.4,
                'subsidy' => 725.9511077543872,
            ],
            [
                'zip_code' => '01740',
                'area_colony_type' => 'A',
                'land_price' => 65123.25,
                'ground_area' => 25,
                'construction_area' => 83,
                'subsidy' => 398.958534422424,
            ],
            [
                'zip_code' => '01740',
                'area_colony_type' => 'C',
                'land_price' => 343850.76,
                'ground_area' => 132,
                'construction_area' => 121,
                'subsidy' => 965.555114149104,
            ],
            [
                'zip_code' => '01740',
                'area_colony_type' => 'A',
                'land_price' => 78147.9,
                'ground_area' => 30,
                'construction_area' => 105,
                'subsidy' => 534.57574001688,
            ],
            [
                'zip_code' => '01740',
                'area_colony_type' => 'A',
                'land_price' => 643964.7453,
                'ground_area' => 247.21,
                'construction_area' => 394.27,
                'subsidy' => 1995.62983975512646,
            ],
            [
                'zip_code' => '01740',
                'area_colony_type' => 'E',
                'land_price' => 1203946.5474,
                'ground_area' => 462.18,
                'construction_area' => 432.14,
                'subsidy' => 1388.4353897486152,
            ],
            [
                'zip_code' => '01740',
                'area_colony_type' => 'E',
                'land_price' => 156295.8,
                'ground_area' => 60,
                'construction_area' => 109,
                'subsidy' => 634.3402782582,
            ],
        ]);
    }
}
