<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Http\Requests\Setup\AcademicYearCreate;
use App\Http\Requests\Setup\CalendarEventsCreate;
use App\Http\Requests\Setup\GradeLevelsCreate;
use App\Http\Requests\Setup\MarkingPeriodCreate;
use App\Http\Requests\Setup\MarkingPeriodEdit;
use App\Http\Requests\Setup\SchoolRecordCreate;
use App\Http\Requests\Setup\SchoolRecordUpdate;
use App\Models\AcademicCalendar;
use App\Models\CalendarEvents;
use App\Models\ClassPeriods;
use App\Models\Classrooms;
use App\Models\GradeLevels;
use App\Models\MarkingPeriods;
use App\Models\School;
use App\Models\system_preferences;
use App\Repositories\LocationRepo;
use App\Repositories\SchoolRepo;
use Barryvdh\Debugbar\Facades\Debugbar;
use DebugBar\DebugBar as DebugBarDebugBar;
use Illuminate\Http\Request;
use Mockery\Undefined;

class setupController extends Controller
{
    protected $loc, $school;
    public function __construct(LocationRepo $loc, SchoolRepo $school)
    {
        $this->middleware('super_admin',['only'=>['create_school','remove_school','update_school']]);
        $this->middleware('teamSA',['only'=>['school_info','school_preferences']]);
        $this->loc = $loc;
        $this->school = $school;
    }

    //schools
    public function create_school()
    {
        $data = [];
        $data['nationals'] = $this->loc->getAllNationals();
        $data['states'] = $this->loc->getStates();
        $data['schools'] = $this->school->all();
        return view('pages.support_team.school_setup.school.create',$data);
    }

    public function update_school(SchoolRecordUpdate $req, $school_id)
    {
        $school = $this->school->find(($school_id));
        $data = $req->only(['name','address','email','principal','phone','telephone','nationality','state','lga','logo','active']);
        $active = $req->has('active') && $req->input('active') === 'on' ? 1 : 0;
        $data['active'] = $active;
        if ($active == 1) {
            // meaning we want to remove other active schools
            $this->school->setNewActive($school_id);
        }
        $school->fill($data);
        $school->save();
        return back()->with('flash_success',__('msg.update_ok'));
    }

    public function remove_school($id)
    {

        $school = $this->school->find($id);
        if($school->active == 1){
            return back()->with('flash_warning',__('msg.denied'));
        }else{
            $this->school->delete($school->id);
            return back()->with('flash_success', __('msg.del_ok'));
        }

    }

    public function change_school_active($school_id)
    {
        $this->school->setNewActive($school_id);
        return Qs::jsonUpdateOk();
    }
    public function change_academic_year_active($academic_year_id)
    {
        $this->school->setNewAcademicYearDefault($academic_year_id);
        return Qs::jsonUpdateOk();
    }

    public function school_store(SchoolRecordCreate $req)
    {
        $data = $req->only(['name','address','email','principal','phone','telephone','nationality','state','lga','logo','active','generic_name']);
        $active = $req->has('active') && $req->input('active') === 'on' ? 1 : 0;
        $data['active'] = $active;
        $data['maintenance']=0;
        if ($active == 1) {
            // meaning we want to remove other active schools
            if($this->school->all()->count() == 0){
                // meaning that this is a new registration and no data exists before
            }else{
                $this->school->changeActive();
            }

        }
        $this->school->createRecord($data);
        return Qs::jsonStoreOk();
    }
    // pages
    public function calendar()
    {
        // $data = $this->school->ActiveSchoolAcademicYear();
        return view('pages.support_team.school_setup.calendar');
    }
    public function periods()
    {
        return view('pages.support_team.school_setup.class_period');
    }
    public function grade_levels()
    {
        return view('pages.support_team.school_setup.grade_levels');
    }
    public function classrooms()
    {
        return view('pages.support_team.school_setup.classrooms');
    }
    public function marking_period()
    {
        return view('pages.support_team.school_setup.marking_periods');
    }
    public function school_info()
    {
        $data = [];
        $data['nationals'] = $this->loc->getAllNationals();
        $data['states'] = $this->loc->getStates();
        $data['schools'] = $this->school->all();
        $data['activeSchool']=Qs::findActiveSchool();
        if(count($data['activeSchool']) == 0)
        {
            return redirect('/setup/schools/create')->with('flash_warning','No School Registered');
        }
        return view('pages.support_team.school_setup.school.index',$data);
    }

    public function school_preferences()
    {
        $data = Qs::getSchoolPreferences();
        return view('pages.support_team.school_setup.school.preferences',['data'=>$data]);
    }
    public function update_school_preference(Request $req,$key,$value){
        Qs::getSchoolPreferences()[0]->fill([$key=>$value])
            ->save();
        return back()->with('flash_success',__('msg.update_ok'));
    }
    public function saveAcademicYear(AcademicYearCreate $req){
        $data = $req->all();
        $active_school = Qs::findActiveSchool();
        if($data['school_id']= $active_school[0]->id){
            $this->school->createAcademicYear($data);
            return back()->with('flash_success',__('msg.store_ok'));
        }else{
            return back()->with('flash_warning',__('No active School. Contact Administrator for solutions.'));
        };
    }

    public function createCalendarEvents(CalendarEventsCreate $req){
        $data = $req->all();
        $active_school = Qs::findActiveSchool();
        $active_acad_year = Qs::getActiveAcademicYear();
        $data['school_id']=$active_school[0]->id;
        $data['acad_year_id']=$active_acad_year[0]->id;
        CalendarEvents::create($data);
        return back()->with('flash_success',__('msg.store_ok'));
    }

    public function addMarkingPeriod(MarkingPeriodCreate $req){
        MarkingPeriods::create($req->all());
        return back()->with('flash_success',__('msg.store_ok'));
    }

    public function get_semester_quarters($sem_id){
        return response()->json(['semester_quarter'=>Qs::getSemesterQuaters($sem_id),'semester'=>Qs::getMarkingPeriod($sem_id)]);
    }
    public function getMarkingPeriod($mp_id){
        return response()->json(Qs::getMarkingPeriod($mp_id));
    }
    public function MarkingPeriodEdit(Request $req){
        $id =$req->data['id'];
        $markingPeriod= Qs::getMarkingPeriod($id);
        $markingPeriod[0]->fill($req->data);
        $markingPeriod[0]->save();
        return back()->with('flash_success',__('msg.update_ok'));
    }
    public function addClassPeriod(Request $req){
        ClassPeriods::create($req->all());

        return back()->with('flash_success',__('msg.store_ok'));
    }

    public function updateClassPeriod(Request $req,$period_id){
        $classPeriod= ClassPeriods::find($period_id);
        $classPeriod->update($req->data);
        $classPeriod->save();
    }
    public function deleteClassPeriod(Request $req,$period_id){
        $classPeriod= ClassPeriods::find($period_id);
        $classPeriod->delete($req->data);
        return back()->with('flash_success',__('msg.del_ok'));
    }

    public function addGradeLevels(Request $req){
        GradeLevels::create($req->all());
        return back()->with('flash_success',__('msg.store_ok'));
    }
    public function updateGradeLevels(Request $req,$grade_id){
        $gradeLevel= GradeLevels::find($grade_id);
        $gradeLevel->update($req->data);
        $gradeLevel->save();
    }
    public function deleteGradeLevels(Request $req,$grade_id){
        $gradeLevel= GradeLevels::find($grade_id);
        $gradeLevel->delete($req->data);
        return back()->with('flash_success',__('msg.del_ok'));
    }

    public function addClassrooms(Request $req){
        Classrooms::create($req->all());
        return back()->with('flash_success',__('msg.store_ok'));
    }

    public function updateClassrooms(Request $req,$room_id){
        $room = Classrooms::find($room_id);
        $room->update($req->data);
        $room->save();
    }

    public function deleteClassrooms(Request $req,$room_id){
        $room = Classrooms::find($room_id);
        $room->delete();
        return back()->with('flash_success',__('msg.del_ok'));
    }

    public function status(Request $req){
        return view('pages.support_team.status',['errorObject'=>$req['errorObject']]);
    }
}
