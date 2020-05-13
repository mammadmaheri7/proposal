<?php

use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proposals')->insert([
            'persian_title' => 'عنوان فارسی',
            'english_title' => 'title',
            'persian_keywords'=>'کلمات کلیدی',
            'english_keywords'=>'english k1,englishk2',
            'type'=>'bonyadi',
            'filename'=>'filename is here',
            'year'=>1399,
            'user_id'=>factory(App\Student::class)->create(),
        ]);
    }
}
