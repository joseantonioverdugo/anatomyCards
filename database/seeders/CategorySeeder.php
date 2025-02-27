<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Cabeza y Cuello']);
        Category::create(['name' => 'Dorso y Médula Espinal']);
        Category::create(['name' => 'Tórax']);
        Category::create(['name' => 'Abdomen']);
        Category::create(['name' => 'Pelvis y Periné']);
        Category::create(['name' => 'Miembro Superior']);
        Category::create(['name' => 'Miembro Inferior']);
    }
}
