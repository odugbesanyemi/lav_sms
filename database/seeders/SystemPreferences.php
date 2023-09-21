<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemPreferences extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['school_id'=>'1','maintenance_status'=>'0','maintenance_message'=>'<div><h2>Ongoing Maintenance</h2><p>Kindly check back later or contact Administrator.</p></div> ','allow_email'=>'0','notify_email'=>'','half_day_minutes'=>'0','full_day_minutes'=>'0'],
        ];
        DB::table('system_preferences')->insert($data);
    }
}
