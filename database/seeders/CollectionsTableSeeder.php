<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('collections')->insert([
            ['name' => 'Зима'],
            ['name' => 'Лето'],
            ['name' => 'Осень'],
            ['name' => 'Весна'],
        ]);
    }
}
