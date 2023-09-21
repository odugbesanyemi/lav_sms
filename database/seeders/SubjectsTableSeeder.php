<?php

namespace Database\Seeders;

use App\Models\GradeLevels;
use App\Models\MyClass;
use App\Models\School;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->delete();

        $this->createSubjects();
    }

    protected function createSubjects()
    {
        $subjects = ['English Language', 'Mathematics'];
        $sub_slug = ['Eng', 'Math'];
        $teacher_id = User::where(['user_type' => 'teacher'])->first()->id;
        $school_id = School::where(['active' => '1'])->first()->id;
        $my_classes = GradeLevels::all();

        foreach ($my_classes as $my_class) {

            $data = [

                [
                    'name' => $subjects[0],
                    'slug' => $sub_slug[0],
                    'school_id'=>$school_id,
                    'my_class_id' => $my_class->id,
                    'teacher_id' => $teacher_id
                ],

                [
                    'name' => $subjects[1],
                    'slug' => $sub_slug[1],
                    'school_id'=>$school_id,
                    'my_class_id' => $my_class->id,
                    'teacher_id' => $teacher_id
                ],

            ];

            DB::table('subjects')->insert($data);
        }

    }

}
