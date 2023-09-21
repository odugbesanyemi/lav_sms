<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class schoolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data =[
            ['name'=>'Kingsmead Junior College','address'=>'Erunwen, Ikorodu','email'=>'college@kingsmeadschools.org.ng','generic_name'=>'Kingsmead College','principal'=>'','phone'=>'','active'=>'1','maintenance'=>'0'],
        ];

        DB::table('schools')->insert($data);
    }
}
