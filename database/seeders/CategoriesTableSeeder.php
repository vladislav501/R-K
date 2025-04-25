<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Женщинам'],
            ['name' => 'Мужчинам'],
            ['name' => 'Акция'],
            ['name' => 'Аксессуары'],
            ['name' => 'Обувь'],
            ['name' => 'Детям'],
        ]);
    }
}
