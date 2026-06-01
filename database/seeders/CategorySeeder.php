<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Webentwicklung'],
            ['name' => 'KI'],
            ['name' => 'Mobile App'],
            ['name' => 'Datenbanken'],
            ['name' => 'Design'],
            ['name' => 'Software Engineering'],
        ]);
    }
}
