<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin user
        DB::table('users')->insert([
            'email' => 'admin@yahoo.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}
