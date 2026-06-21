<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Angewandte Informatik- und Biowissenschaften'],
            ['name' => 'Medien'],
            ['name' => 'Soziale Arbeit'],
            ['name' => 'Wirtschaftsingenieurwesen'],
            ['name' => 'Ingenieurwissenschaften'],
        ]);
    }
}
