<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FakeUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'test2@test.ru',
            'email_verified_at' => now(),
            'role' => config('constants.role.GUEST'),
            'password' => Hash::make('87654321'),
            'remember_token' => Str::random(100)
        ]);
    }
}
