<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['technology','food','sport','fashion','clothing','jewlery'];

        foreach($categories as $cat){
            \App\Models\Category::create([
                'name' => $cat,
            ]);
        }
    }
}
