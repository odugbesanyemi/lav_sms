<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\Mark;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $my_class, $student;

    public function __construct(MyClassRepo $my_class, StudentRepo $student)
    {
        $this->middleware('teamSA');

        $this->my_class = $my_class;
        $this->student = $student;
    }

    public function promotion($fc = NULL, $fs = NULL, $tc = NULL, $ts = NULL)
    {
        $d['old_year'] = $old_yr = Qs::getActiveAcademicYear()[0]->title;
        $old_yr = explode('/', $old_yr);
        $d['new_year'] = ++$old_yr[0].'/'.++$old_yr[1];
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['selected'] = false;

        if($fc && $fs && $tc && $ts){
            $d['selected'] = true;
            $d['fc'] = $fc;
            $d['fs'] = $fs;
            $d['tc'] = $tc;
            $d['ts'] = $ts;
            $d['students'] = $sts = $this->student->getRecord(['my_class_id' => $fc,'school_id'=> Qs::findActiveSchool()[0]->id, 'section_id' => $fs, 'acad_year_id' => Qs::getActiveAcademicYear()[0]->id])->get();

            if($sts->count() < 1){
                return redirect()->route('students.promotion')->with('flash_warning', __('msg.nstp'));
            }
        }

        return view('pages.support_team.students.promotion.index', $d);
    }

    public function selector(Request $req)
    {
        return redirect()->route('students.promotion', [$req->fc, $req->fs, $req->tc, $req->ts]);
    }

    public function promote(Request $req, $fc, $fs, $tc, $ts)
    {
        $oy = Qs::getActiveAcademicYear()[0]->title; $d = [];
        debugbar()->log($oy);
        $old_yr = explode('/', $oy);
        $ny = ++$old_yr[0].'/'.++$old_yr[1];

        // check the academic Calendar to see if a title (ny) exists if it doesn't, create one
        $new_acad_year = Qs::findAcademicYearByTitle($ny);
        if($new_acad_year->count() < 1){
            // meaning there is no saved academic year for the new promoted year hence create
            $data = [
                'school_id'=> Qs::findActiveSchool()[0]->id,
                'title' => $ny,
                'start_date'=> date('Y-m-d'),
                'end_date'=>date('Y-m-d', strtotime(date('Y-m-d') . ' +3 months')),
                'default'=>'0',
            ];
            AcademicCalendar::create($data);
        }else{
            // return redirect()->route('students.promotion')->with('flash_danger', __('Cannot Create Academic Calendar.'));
            // do nothing since it has already been created
        }

        $students = $this->student->getRecord(['my_class_id' => $fc, 'section_id' => $fs, 'acad_year_id' => Qs::getActiveAcademicYear()[0]->id ])->get()->sortBy('user.name');


        if($students->count() < 1){
            return redirect()->route('students.promotion')->with('flash_danger', __('msg.srnf'));
        }
        foreach($students as $st){
            $p = 'p-'.$st->id;
            $p = $req->$p;
            if($p === 'P'){ // Promote
                $d['my_class_id'] = $tc;
                $d['section_id'] = $ts;
                $d['acad_year_id'] =Qs::findAcademicYearByTitle($ny)[0]->id;
            }
            if($p === 'D'){ // Don't Promote
                $d['my_class_id'] = $fc;
                $d['section_id'] = $fs;
                $d['acad_year_id'] = Qs::findAcademicYearByTitle($ny)[0]->id;
            }
            if($p === 'G'){ // Graduated
                $d['my_class_id'] = $fc;
                $d['section_id'] = $fs;
                $d['grad'] = 1;
                $d['grad_date'] = $oy;
            }

            $this->student->updateRecord($st->id, $d);

//          Insert New Promotion Data
            $promote['from_class'] = $fc;
            $promote['school_id'] = Qs::findActiveSchool()[0]->id;
            $promote['from_section'] = $fs;
            $promote['grad'] = ($p === 'G') ? 1 : 0;
            $promote['to_class'] = in_array($p, ['D', 'G']) ? $fc : $tc;
            $promote['to_section'] = in_array($p, ['D', 'G']) ? $fs : $ts;
            $promote['student_id'] = $st->user_id;
            $promote['from_session'] = $oy;
            $promote['to_session'] = $ny;
            $promote['status'] = $p;

            $this->student->createPromotion($promote);
        }
        return redirect()->route('students.promotion')->with('flash_success', __('msg.update_ok'));
    }

    public function manage()
    {
        $data['promotions'] = $this->student->getAllPromotions();
        $data['old_year'] = Qs::getCurrentSession();
        $data['new_year'] = Qs::getNextSession();

        return view('pages.support_team.students.promotion.reset', $data);
    }

    public function reset($promotion_id)
    {
        $this->reset_single($promotion_id);
        return redirect()->route('students.promotion_manage')->with('flash_success', __('msg.update_ok'));
    }

    public function reset_all()
    {
        $next_session = Qs::getNextSession();
        $where = ['from_session' => Qs::getCurrentSession(), 'to_session' => $next_session];
        $proms = $this->student->getPromotions($where);

        if ($proms->count()){
          foreach ($proms as $prom){
              $this->reset_single($prom->id);

              // Delete Marks if Already Inserted for New Session
              $this->delete_old_marks($prom->student_id, $next_session);
          }
        }

        return Qs::jsonUpdateOk();
    }

    protected function delete_old_marks($student_id, $year)
    {
        Mark::where(['student_id' => $student_id, 'year' => $year])->delete();
    }

    protected function reset_single($promotion_id)
    {
        $prom = $this->student->findPromotion($promotion_id);

        $data['my_class_id'] = $prom->from_class;
        $data['section_id'] = $prom->from_section;
        $data['acad_year_id'] = Qs::findAcademicYearByTitle($prom->from_session)[0]->id ;
        $data['grad'] = 0;
        $data['grad_date'] = null;

        $this->student->update(['user_id' => $prom->student_id], $data);

        return $this->student->deletePromotion($promotion_id);
    }
}
