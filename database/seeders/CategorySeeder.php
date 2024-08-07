<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'title' => 'История',
            'slug' => Str::of('История')->slug('-'),
        ]);

        DB::table('categories')->insert([
            'title' => 'Медицина',
            'slug' => Str::of('Медицина')->slug('-'),
        ]);

        DB::table('categories')->insert([
            'title' => 'Разное',
            'slug' => Str::of('Разное')->slug('-'),
        ]);

        DB::table('categories')->insert([
            'title' => 'Хроника',
            'slug' => Str::of('Хроника')->slug('-'),
        ]);

        DB::table('categories')->insert([
            'title' => 'Философия',
            'slug' => Str::of('Философия')->slug('-'),
        ]);

        DB::table('categories')->insert([
            'title' => 'Юмор',
            'slug' => Str::of('Юмор')->slug('-'),
        ]);
    }
}
