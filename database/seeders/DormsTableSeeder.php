<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dorms')->delete();
        $ActiveSchool = DB::table('schools')->where('active','1')->first();

        $data = [
            ['school_id'=>$ActiveSchool->id,'name' => 'Hostel 1'],
            ['school_id'=>$ActiveSchool->id,'name' => 'Hostel 2'],
            ['school_id'=>$ActiveSchool->id,'name' => 'Hostel 3'],
        ];
        DB::table('dorms')->insert($data);
    }
}
