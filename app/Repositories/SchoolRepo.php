<?php

namespace App\Repositories;

use App\Models\AcademicCalendar;
use App\Models\School;
use App\Models\system_preferences;

class SchoolRepo {
    public function createRecord ($data){
        $school = School::create($data);
        if($school){
            // succes creating the school
            // continue to add prefernces
            $preferenceData = [
                'school_id'=>$school->id,
                'maintenance_status'=>0,
                'maintenance_message'=>'',
                'allow_email'=>0,
                'notify_email'=>'',
                'half_day_minutes'=>0,
                'full_day_minutes'=>0,
            ];
            system_preferences::create($preferenceData);
        }
    }
    public function all(){
        // view all schools
        return School::all();
    }
    public function find($id)
    {
        return School::find($id);
    }
    public function delete($id)
    {
        return School::destroy($id);
    }
    public function findActiveSchool(){
        return School::where('active', 1)->get();
    }
    public function changeActive()
    {
        $currentActive = self::findActiveSchool();
        if($currentActive){
            $currentActive[0]->active = 0;
            $currentActive[0]->save();
        }else{
            debugbar()->log("didn't do anything");
        }

    }
    public function setNewActive($id)
    {
        $currentActive = self::findActiveSchool();
        if($currentActive[0]->id === $id)//meaning that the school is the current active so do nothing
        {
            return;
        }
        self::changeActive();
        $newRow = self::find($id);
        $newRow->active = 1;
        $newRow->save();
    }

    public function createAcademicYear($data)
    {
        AcademicCalendar::create($data);
    }

    public function ActiveSchoolAcademicYear()
    {
        $active_school_id = self::findActiveSchool()[0]->id;
        return AcademicCalendar::where('default',1)
            ->where('school_id',$active_school_id)
            ->get();
    }
    public function changeAcademicYearDefault()
    {
        $currentActive = self::ActiveSchoolAcademicYear();
        if($currentActive){
            $currentActive[0]->default = 0;
            $currentActive[0]->save();
        }else{
            debugbar()->log("didn't do anything");
        }

    }

    public function setNewAcademicYearDefault($id)
    {
        self::changeAcademicYearDefault();
        $newRow = AcademicCalendar::find($id);
        $newRow->default = 1;
        $newRow->save();
    }
}
