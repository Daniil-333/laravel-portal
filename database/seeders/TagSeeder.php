<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('tags')->insert([
            'title' => 'IT',
            'slug' => Str::of('IT')->slug('-'),
        ]);

        DB::table('tags')->insert([
            'title' => '9 мая',
            'slug' => Str::of('9 мая')->slug('-'),
        ]);

        DB::table('tags')->insert([
            'title' => 'СССР',
            'slug' => Str::of('СССР')->slug('-'),
        ]);
    }
}
