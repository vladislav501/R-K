<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ClothingType;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Size;
use App\Models\PickupPoint;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Category::insert([
            ['name' => 'Мужчины', 'description' => 'Одежда для мужчин', 'is_active' => true],
            ['name' => 'Женщины', 'description' => 'Одежда для женщин', 'is_active' => true],
            ['name' => 'Дети', 'description' => 'Одежда для детей', 'is_active' => true],
            ['name' => 'Аксессуары', 'description' => 'Аксуссуары', 'is_active' => true],
            ['name' => 'Обувь', 'description' => 'Обувь', 'is_active' => true],
        ]);

        $colors = ['Чёрный', 'Белый', 'Синий', 'Красный', 'Зелёный', 'Жёлтый', 'Серый', 'Розовый', 'Бежевый', 'Оранжевый'];
        foreach ($colors as $color) {
            Color::create(['name' => $color]);
        }

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        foreach ($sizes as $size) {
            Size::create(['name' => $size]);
        }

        $types = [
            'Футболка', 'Джинсы', 'Куртка', 'Рубашка', 'Пальто',
            'Шорты', 'Юбка', 'Пиджак', 'Свитшот', 'Худи',
            'Топ', 'Майка', 'Плащ', 'Комбинезон', 'Толстовка',
            'Леггинсы', 'Штаны', 'Брюки', 'Поло', 'Спортивный костюм'
        ];
        foreach ($types as $type) {
            ClothingType::create(['name' => $type]);
        }

        $collections = ['Лето 2025', 'Осень 2025', 'Зима 2025', 'Весна 2025', 'Базовая коллекция'];
        foreach ($collections as $collection) {
            Collection::create(['name' => $collection, 'description' => $collection . ' — сезонная коллекция']);
        }

        $brands = [
            'Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour',
            'Levi\'s', 'H&M', 'Zara', 'Bershka', 'Pull&Bear',
            'Uniqlo', 'Calvin Klein', 'Tommy Hilfiger', 'Guess', 'New Balance',
            'Asics', 'Columbia', 'The North Face', 'Lacoste', 'Champion'
        ];
        foreach ($brands as $brand) {
            Brand::create(['name' => $brand]);
        }

        $pickupPoints = [
            'grodno' => [
                ['ул. Советская, 1', '10:00–20:00'],
                ['ул. Ожешко, 10', '09:00–19:00'],
                ['ул. Горького, 15', '11:00–21:00'],
            ],
            'minsk' => [
                ['пр-т Независимости, 50', '09:00–22:00'],
                ['ул. Немига, 12', '10:00–20:00'],
                ['ул. Сурганова, 30', '08:00–20:00'],
            ],
            'brest' => [
                ['ул. Советская, 2', '10:00–20:00'],
                ['ул. Московская, 22', '09:00–18:00'],
            ],
            'gomel' => [
                ['ул. Советская, 17', '10:00–19:00'],
            ],
            'mogilev' => [
                ['ул. Первомайская, 9', '09:00–17:00'],
            ],
            'vitebsk' => [
                ['ул. Ленина, 5', '10:00–20:00'],
            ],
        ];

        foreach ($pickupPoints as $city => $points) {
            foreach ($points as $index => [$address, $hours]) {
                $manager = User::create([
                    'first_name' => ucfirst($city),
                    'last_name' => 'Manager ' . ($index + 1),
                    'email' => $city . ($index + 1) . '@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'manager',
                ]);

                PickupPoint::create([
                    'name' => ucfirst($city) . ' ' . ($index + 1),
                    'address' => $address,
                    'hours' => $hours,
                    'user_id' => $manager->id,
                ]);
            }
        }
    }
}
