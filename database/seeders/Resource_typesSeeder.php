<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class Resource_typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            ['name'=>'e-notes','Created_at'=> Date('Y-h-m'),'Updated_at'=> Date('Y-h-m')],
            ['name'=>'work-scheme','Created_at'=> Date('Y-h-m'),'Updated_at'=> Date('Y-h-m')],
            ['name'=>'past-question','Created_at'=> Date('Y-h-m'),'Updated_at'=> Date('Y-h-m')],
        ];
        DB::table('resource_type')->insert($data);
    }
}
