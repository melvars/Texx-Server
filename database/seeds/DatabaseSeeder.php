<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 0,
            'name' => 'genesis',
            'email' => ' ',
            'password' => ' ',
            'prevHash' => '0',
            'hash' => Hash::make('genesis')
        ]);

        DB::table('users')->insert([
            'name' => 'Marvin Borner',
            'email' => 'marvin@borners.de',
            'password' => Hash::make('password'),
            'prevHash' => Hash::make('genesis'),
            'hash' => Hash::make(Hash::make('genesis') . 'Marvin Borner' . 'marvin@borners.de' . Hash::make('password')), // hashing: prev hash, all fields in current 'block'
            'api_token' => "password"
        ]);
    }
}
