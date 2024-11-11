<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
                ['name' => 'Samsung', 'slug' => 'samsung', 'is_active' => true],
                ['name' => 'Apple', 'slug' => 'apple', 'is_active' => true],
                ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'is_active' => true],
                ['name' => 'Motorola', 'slug' => 'motorola', 'is_active' => true],
            ]);
    }
}
