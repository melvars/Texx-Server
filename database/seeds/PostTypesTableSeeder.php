<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PostTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_types')->insert([
            'type' => 'Media'
        ]);

        DB::table('post_types')->insert([
            'type' => 'Text'
        ]);
    }
}
