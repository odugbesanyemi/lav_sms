<?php

namespace App\Helpers;

use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\MarkingPeriods;
use App\Models\MarkPreferences;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class Mk extends Qs
{
    public static function examIsLocked()
    {
        return self::getSetting('lock_exam');
    }

    public static function getRemarks()
    {
        return ['Average', 'Credit', 'Distinction', 'Excellent', 'Fail', 'Fair', 'Good', 'Pass', 'Poor', 'Very Good', 'Very Poor'];
    }

    /** ADD ORDINAL SUFFIX TO POSITION **/
    public static function getSuffix($number)
    {
        if($number < 1){ return NULL;}

        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . '<sup>th</sup>';
        else
            return $number . '<sup>' . $ends[$number % 10] . '</sup>';
    }

    /*Get Subject Total Per Term*/
    public static function getSubTotalTerm($st_id, $sub_id, $term, $class_id, $year)
    {
        $d = ['student_id' => $st_id, 'subject_id' => $sub_id, 'my_class_id' => $class_id, 'year' => $year];

        $tex = 'tex'.$term;
        $sub_total = Mark::where($d)->select($tex)->get()->where($tex, '>', 0);
        return $sub_total->count() > 0 ? $sub_total->first()->$tex : '-';
    }

    public static function countDistinctions(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'A%')->orWhere('name', 'LIKE', 'B%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countPasses(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'D%')->orWhere('name', 'LIKE', 'E%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countCredits(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'C%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countFailures(Collection $marks)
    {
        $gradeIDS = Grade::where('name', 'LIKE', 'F%')->get()->pluck('id')->toArray();
        return self::markGradeFilter($marks, $gradeIDS);
    }

    public static function countStudents($exam_id, $class_id, $section_id, $year)
    {
        $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $year];
        return Mark::where($d)->select('student_id')->distinct()->get()->count();
    }

    protected static function markGradeFilter(Collection $marks, $gradeIDS)
    {
        return $marks->filter(function($mks) use ($gradeIDS){
            return in_array($mks->grade_id, $gradeIDS);
        })->count();
    }

    public static function countSubjectsOffered(Collection $mark)
    {
        return $mark->filter(function($mk) {
            return ($mk->tca + $mk->exm) > 0 ;
        })->count();
    }

    /*Get Exam Avg Per Term*/
    public static function getTermAverage($st_id, $term, $year)
    {
        $exam = self::getExamByTerm($term, $year);
        $d = ['exam_id' => $exam->id, 'student_id' => $st_id, 'year' => $year];

        if($term < 3){
            $exr = ExamRecord::where($d);
            $avg = $exr->first()->ave ?: NULL;
            return $avg > 0 ? round($avg, 1) : $avg;
        }

        $mk = Mark::where($d)->whereNotNull('tex3');
        $avg = $mk->select('tex3')->avg('tex3');
        return round($avg, 1);
    }

    public static function getTermTotal($st_id, $term, $year)
    {
        $exam = self::getExamByTerm($term, $year);
        $d = ['exam_id' => $exam->id, 'student_id' => $st_id, 'year' => $year];

        if($term < 3){
            return ExamRecord::where($d)->first()->total ?? NULL;
        }

        $mk = Mark::where($d)->whereNotNull('tex3');
        return $mk->select('tex3')->sum('tex3');
    }

    public static function getExamByTerm($term, $year)
    {
        $d = ['term' => $term, 'year' => $year];
        return Exam::where($d)->first();
    }

    public static function getGradeList($school_id)
    {
        $grades = Grade::where(['school_id' => $school_id])->orderBy('name')->get();

        if($grades->count() < 1){
            $anySchoolId = Grade::first();
            $grades = Grade::where('school_id',$anySchoolId[0]->school_id)->orderBy('name')->get();
        }
        return $grades;
    }

    /**
     * If Class/Section is Changed in Same Year,
     * Delete Marks/ExamRecord of Previous Class/Section
     *
     * @param int $st_id
     * @param int $class_id
     * @return bool
     * @static
     */
    public static function deleteOldRecord($st_id, $class_id)
    {
        $d = ['student_id' => $st_id, 'year' => self::getCurrentSession()];

        $marks = Mark::where('my_class_id', '<>', $class_id)->where($d);
        if($marks->get()->count() > 0){
            $exr = ExamRecord::where('my_class_id', '<>', $class_id)->where($d);
            $marks->delete();
            $exr->delete();
        }
        return true;
    }
    public static function getExamById($id){
        return Exam::find($id)->get();
    }

    public static function getMarkPreference($marking_period_id){
        return MarkPreferences::where(['marking_period_id'=>$marking_period_id,'acad_year_id'=>Qs::getActiveAcademicYear()[0]->id])->get();
    }

    public static function isMarkingPeriod($marking_period_id)
    {
        if(Qs::userIsTeacher()){
            // check if the marking period is within the current time
            $markingPeriod = MarkingPeriods::find($marking_period_id)->get();
            $startDate = $markingPeriod[0]->post_start_date;
            $endDate = $markingPeriod[0]->post_end_date;
            $currentDate = date('Y-m-d');
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);
            $currentDateTime = new DateTime($currentDate);
            if ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
                // echo "The current date is within the range.";
                return true;
            } else {
                // echo "The current date is not within the range.";
                return false;
            }
        }
        return true;
    }
    public static function markingTypeIsSemester($marking_period_id){
        $markingPeriod = MarkingPeriods::where('id',$marking_period_id)->get();
        if($markingPeriod[0]->mp_type === 'semester'){
            return true;
        }else{
            return false;
        }
    }
    public static function markingTypeIsQuarter($marking_period_id)
    {
        $markingPeriod = MarkingPeriods::where('id',$marking_period_id)->get();
        if($markingPeriod[0]->mp_type === 'quarter'){
            return true;
        }else{
            return false;
        }
    }
    public static function markingTypeOrder($marking_period_id)
    {
        $markingPreferences = MarkPreferences::where('marking_period_id',$marking_period_id)->get();
        return $markingPreferences[0]->type_order;
    }

    public static function reportTypeName($marking_period_id)
    {
        if(self::markingTypeIsSemester($marking_period_id)==true){
            switch (self::markingTypeOrder($marking_period_id)) {
                case '1':
                    return 'FIRST TERM';
                    break;
                case '2':
                    return 'SECOND TERM';
                    break;
                default:
                    return 'THIRD TERM';
                    break;
            }
        }
        else{
            switch (self::markingTypeOrder($marking_period_id)) {
                case '1':
                    return 'MID TERM';
                    break;
                case '2':
                    return 'FINAL TERM';
                    break;
            }
        }
    }

    public static function getMarkTotal($st_id,$acad_year_id,$subject_id,$mp_type,$order_type)
    {
        $marks = Mark::where(['student_id'=>$st_id,'acad_year_id'=>$acad_year_id,'subject_id'=>$subject_id])->get();
        foreach ($marks as $mk) {

            $exam_id = $mk->exam_id;
            $marking_period_id = Exam::where('id',$exam_id)->first()->marking_period->id;
            $mark_preferences = self::getMarkPreference($marking_period_id);
            $mark_preferences_type_order = $mark_preferences[0]->type_order;
            if($mark_preferences_type_order==$order_type){
                $ca_score = $mk->ca_score;
                $exam_score = $mk->exam_score;
                $total = $ca_score + $exam_score;
                return $total;
            }
        }
    }

    public static function getGradeDetails($school_id, $num)
    {
        $grades = self::getGradeList($school_id);
        foreach ($grades as $grade) {
            $grade_from = $grade->mark_from;
            $grade_to = $grade->mark_to;
            if($grade_from <= $num && $grade_to >= $num)
            {
                return $grade;
            }
        }
    }
}
