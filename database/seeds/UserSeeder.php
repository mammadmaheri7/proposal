<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //create admin if does not exist
        if(\App\User::where('role_id',1)->count()==0)
        {
            $admin = \App\User::create([
                'first_name' => 'esfehani',
                'last_name' => 'esfehani',
                'email' => 'admin@sbu.ac.ir',
                'password' => bcrypt('789654123'),
                'national_number' => '1111111951111',
                'role_id' => 1
            ]);
        }

        //create students
        $students = factory(App\Student::class,10)
            ->create()
            ->each(function($student){

            });

        $professors = factory(App\Professor::class,10)
            ->create();

    }
}
