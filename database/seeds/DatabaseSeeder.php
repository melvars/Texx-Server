<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'hash' => bcrypt('genesis')
        ]);

        DB::table('users')->insert([
            'name' => 'Marvin Borner',
            'email' => 'marvin@borners.de',
            'password' => bcrypt('password'),
            'hash' => bcrypt(bcrypt('genesis') . 'Marvin Borner'. 'marvin@borners.de'. 'password') // hashing: prev hash, all fields in current 'block'
        ]);
    }
}
