<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
private $tables = [
        'users'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->cleanDatabase(); //removes all content
    	
        //this recreates dummy data in the database. 
        $this->call(UsersTableSeeder::class);
    }

    private function cleanDatabase(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($this->tables as $tableName) {
            DB::table($tableName)->truncate();
        };
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}