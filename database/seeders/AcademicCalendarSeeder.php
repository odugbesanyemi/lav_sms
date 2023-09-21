<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            ['school_id'=>'1','title'=>'2023/2024','start_date'=>'2023-09-04','end_date'=>'2024-07-19','default'=>'1'],
        ];

        DB::table('academic_calendar')->insert($data);
    }
}
