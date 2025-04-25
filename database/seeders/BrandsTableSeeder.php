<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('brands')->insert([
            ['name' => 'Nike'],
            ['name' => 'Adidas'],
            ['name' => 'Puma'],
            ['name' => 'Reebok'],
            ['name' => 'Under Armour'],
            ['name' => 'Levi\'s'],
            ['name' => 'Gucci'],
            ['name' => 'Prada'],
            ['name' => 'Versace'],
            ['name' => 'Chanel'],
        ]);
    }
}
