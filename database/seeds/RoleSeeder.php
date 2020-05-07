<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin','student','group_manager','professor'];
        $descriptions = ['ادمین','دانشجو','مدیرگروه','استاد'];
        foreach (range(0,count($roles)-1) as $index){
            DB::table('roles')->insert([
                'title' => $roles[$index],
                'description' => $descriptions[$index],
            ]);
        }
    }
}
