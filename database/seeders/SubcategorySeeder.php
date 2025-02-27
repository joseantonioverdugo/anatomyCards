<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subcategory;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subcategory::create(['name' => 'Sistema Esquelético']);
        Subcategory::create(['name' => 'Sistema Muscular']);
        Subcategory::create(['name' => 'Sistema Nervioso']);
        Subcategory::create(['name' => 'Sistema Cardiovascular']);
        Subcategory::create(['name' => 'Sistema Respiratorio']);
        Subcategory::create(['name' => 'Sistema Reproductor y Urinario']);
        Subcategory::create(['name' => 'Sistema Digestivo']);
    }
}
