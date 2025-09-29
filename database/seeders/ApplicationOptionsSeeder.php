<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Locator\ApplicationOption;

class ApplicationOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            ['name' => 'P10,000 and below',       'value' => '100',   'validity' => '1 Time validity'],
            ['name' => 'P10,001 to P50,000',      'value' => '200',   'validity' => '1 Time validity'],
            ['name' => 'More Than P50,000',       'value' => '300',   'validity' => '1 Time validity'],
            ['name' => 'Medium construction vehicles', 'value' => '400', 'validity' => '1 Day validity'],
            ['name' => 'Heavy construction vehicles',  'value' => '500',  'validity' => '1 Day validity'],

            ['name' => 'P10,000 and below',       'value' => '150',   'validity' => '5 Time validity'],
            ['name' => 'P10,001 to P50,000',      'value' => '250',   'validity' => '5 Time validity'],
            ['name' => 'More Than P50,000',       'value' => '350',   'validity' => '5 Time validity'],
            ['name' => 'Medium construction vehicles', 'value' => '450', 'validity' => '5 Day validity'],
            ['name' => 'Heavy construction vehicles',  'value' => '550',  'validity' => '5 Day validity'],

           ['name' => 'P10,000 and below',       'value' => '200',   'validity' => '20 Time validity'],
            ['name' => 'P10,001 to P50,000',      'value' => '300',   'validity' => '20 Time validity'],
            ['name' => 'More Than P50,000',       'value' => '400',   'validity' => '20 Time validity'],
            ['name' => 'Medium construction vehicles', 'value' => '500', 'validity' => '20 Day validity'],
            ['name' => 'Heavy construction vehicles',  'value' => '600',  'validity' => '20 Day validity'],
        ];

        foreach ($options as $option) {
            ApplicationOption::create($option);
        }
    }
}
