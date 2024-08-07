<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'test testovich',
            'email' => 'test@test.ru',
            'email_verified_at' => now(),
            'role' => config('constants.role.MODERATOR'),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(100)
        ]);
    }
}
