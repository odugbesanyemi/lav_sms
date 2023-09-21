<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Qs;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->delete();

        $this->createSkills();
    }

    protected function createSkills()
    {
        $school_id = DB::table('schools')->where(['active'=>Qs::findActiveSchool()[0]->id])->first();
        $types = ['AF', 'PS']; // Affective & Psychomotor Traits/Skills
        $d = [

            ['school_id'=>$school_id->id, 'name' => 'PUNCTUALITY', 'skill_type' => $types[0] ],
            ['school_id'=>$school_id->id, 'name' => 'NEATNESS', 'skill_type' => $types[0] ],
            ['school_id'=>$school_id->id, 'name' => 'HONESTY', 'skill_type' => $types[0] ],
            ['school_id'=>$school_id->id, 'name' => 'RELIABILITY', 'skill_type' => $types[0] ],
            ['school_id'=>$school_id->id, 'name' => 'RELATIONSHIP WITH OTHERS', 'skill_type' => $types[0] ],
            ['school_id'=>$school_id->id, 'name' => 'POLITENESS', 'skill_type' => $types[0] ],
            ['school_id'=>$school_id->id, 'name' => 'ALERTNESS', 'skill_type' => $types[0] ],
            ['school_id'=>$school_id->id, 'name' => 'HANDWRITING', 'skill_type' => $types[1] ],
            ['school_id'=>$school_id->id, 'name' => 'GAMES & SPORTS', 'skill_type' => $types[1] ],
            ['school_id'=>$school_id->id, 'name' => 'DRAWING & ARTS', 'skill_type' => $types[1] ],
            ['school_id'=>$school_id->id, 'name' => 'PAINTING', 'skill_type' => $types[1] ],
            ['school_id'=>$school_id->id, 'name' => 'CONSTRUCTION', 'skill_type' => $types[1] ],
            ['school_id'=>$school_id->id, 'name' => 'MUSICAL SKILLS', 'skill_type' => $types[1] ],
            ['school_id'=>$school_id->id, 'name' => 'FLEXIBILITY', 'skill_type' => $types[1] ],

        ];
        DB::table('skills')->insert($d);
    }

}
