<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ClothingType;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Size;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);

        Brand::create(['name' => 'Nike']);
        Brand::create(['name' => 'Adidas']);
        Category::create(['name' => 'Мужчины', 'is_active' => true]);
        Category::create(['name' => 'Женщины', 'is_active' => true]);
        ClothingType::create(['name' => 'Футболка']);
        ClothingType::create(['name' => 'Джинсы']);
        Collection::create(['name' => 'Лето 2025']);
        Color::create(['name' => 'Черный']);
        Color::create(['name' => 'Белый']);
        Size::create(['name' => 'M']);
        Size::create(['name' => 'L']);
    }
}