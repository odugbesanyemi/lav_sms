<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Qs;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->delete();

        $this->createGrades();
    }

    protected function createGrades()
    {
        $activeSchool = DB::table('schools')->where(['active'=>Qs::findActiveSchool()[0]->id])->first();
        $d = [

            ['school_id'=>$activeSchool->id, 'name' => 'A', 'mark_from' => 70, 'mark_to' => 100, 'remark' => 'Excellent'],
            ['school_id'=>$activeSchool->id,  'name' => 'B', 'mark_from' => 60, 'mark_to' => 69, 'remark' => 'Very Good'],
            ['school_id'=>$activeSchool->id,  'name' => 'C', 'mark_from' => 50, 'mark_to' => 59, 'remark' => 'Good'],
            ['school_id'=>$activeSchool->id,  'name' => 'D', 'mark_from' => 45, 'mark_to' => 49, 'remark' => 'Pass'],
            ['school_id'=>$activeSchool->id,  'name' => 'E', 'mark_from' => 40, 'mark_to' => 44, 'remark' => 'Poor'],
            ['school_id'=>$activeSchool->id,  'name' => 'F', 'mark_from' => 0, 'mark_to' => 39, 'remark' => 'Fail'],


        ];
        DB::table('grades')->insert($d);
    }
}
