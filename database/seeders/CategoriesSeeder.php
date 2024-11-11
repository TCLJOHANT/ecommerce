<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Telefonos', 'slug' => 'telefonos'],
            ['name' => 'Televisores', 'slug' => 'televisores'],
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Computadores', 'slug' => 'computadores'],
        ]);
    }
}
