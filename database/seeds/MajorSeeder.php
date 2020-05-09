<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = ['هوش مصنوعی و رباتیکز','شبکه','نرم افزار','معماری کامپیوتر'];
        $codes = ['1000','1001','1002','1003'];
        foreach (range(0,count($titles)-1) as $index){
            DB::table('majors')->insert([
                'title' => $titles[$index],
                'code' => $codes[$index],
            ]);
        }
    }
}
