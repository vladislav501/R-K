<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('types')->insert([
            ['name' => 'Куртка'],
            ['name' => 'Штаны'],
            ['name' => 'Нижнее бельё'],
            ['name' => 'Футболка'],
            ['name' => 'Рубашка'],
            ['name' => 'Шорты'],
            ['name' => 'Юбка'],
            ['name' => 'Платье'],
            ['name' => 'Костюм'],
            ['name' => 'Пальто'],
        ]);
    }
}
