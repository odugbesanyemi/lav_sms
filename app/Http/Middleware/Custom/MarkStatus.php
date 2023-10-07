<?php

namespace App\Http\Middleware\Custom;

use App\Models\Grade;
use App\Models\MarkingPeriods;
use App\Models\MarkPreferences;
use App\Models\Remarks;
use App\Models\Skill;
use Closure;
use Illuminate\Http\Request;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use Mk;
use Qs;

class MarkStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function handle(Request $request, Closure $next)
    {
        // purpose of this middleware is to check if any of the following
        $errors = [
            'Subjects'=>['completed'=>false,'message'=>'You do not have Subjects in your school Kindly create to continue','route'=>['subjects.index','subjects.store']],
            'Preferences'=>['completed'=>false,'message'=>'One or more of your Marking Periods are missing important settings.','route'=>['marks.setup.preferences','marks.setup.preferences-select','marks.setup.preferences-update']],
            'Grades'=>['completed'=>false,'message'=>'There are no Grades in selected School Kindly Create at least 4 to continue.','route'=>['grades.index','grades.store']],
            'Skills'=>['completed'=>false,'message'=>'Skills are important to grade Students behavioural traits. Create at least 2 Now to continue.','route'=>['marks.setup.manage-skills','marks.setup.add-skill']],
            'comments'=>['completed'=>false,'message'=>'Comments are important for reports. Create one Now to continue.','route'=>['marks.setup.manage-remarks','marks.setup.add-remark']],
        ];

        $routeName = $request->route()->getName();
        $errorsObject = json_decode(json_encode($errors));
        function checkSelected($obj,$routeName){
            foreach ($obj as $key => $value) {
                if(in_array($routeName,$value->route)){
                    $selectedError= $value;
                    return $selectedError;
                }else{
                    return null;
                }
            }
        }
        $markingPeriods = MarkingPeriods::where('school_id',Qs::findActiveSchool()[0]->id)->get()->pluck('id');
        $markingPreferences = MarkPreferences::where(['school_id'=>Qs::findActiveSchool()[0]->id])->get()->pluck('marking_period_id');
        $markingPeriodScores = MarkPreferences::where(['school_id'=>Qs::findActiveSchool()[0]->id])->get()->pluck('ca_final_score','exam_final_score')->toArray();
        $isMatchMarkingPeriod = $markingPreferences == $markingPeriods;
        $containsNull = false;
        foreach($markingPeriodScores as $key=>$value){
            if($value === null){
                $containsNull = true;
                break; //exit the loop once a null value is found
            }

        }
        // Subjects should be at least 1
        if(count($this->my_class->getAllSubjects())==0){
            // meaning there are no subjects in this school
            return redirect()->route('marks.status',['errorObject'=>$errorsObject]);
        }
        // Each Mark Periods should have a mark preference
        elseif (!$isMatchMarkingPeriod || $containsNull) {
            $errorsObject->Subjects->completed = true;
            $errorsObject->Subjects->message = 'Subjects Found';
            if(in_array($routeName,$errorsObject->Preferences->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('marks.status',['errorObject'=>$errorsObject]);
            }
        }
        // Grades should be at least 4
        elseif (count(Grade::where('school_id',Qs::findActiveSchool()[0]->id)->get()) < 4) {
            $errorsObject->Subjects->completed = true;
            $errorsObject->Subjects->message = 'Subjects Found';
            $errorsObject->Preferences->completed = true;
            $errorsObject->Preferences->message = 'Marking period Preferences Set';
            if(in_array($routeName,$errorsObject->Grades->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('marks.status',['errorObject'=>$errorsObject]);
            }
        }
        elseif (count(Skill::where('school_id',Qs::findActiveSchool()[0]->id)->get()) < 2) {
            $errorsObject->Subjects->completed = true;
            $errorsObject->Subjects->message = 'Subjects Found';
            $errorsObject->Preferences->completed = true;
            $errorsObject->Preferences->message = 'Marking Period Preferences Set';
            $errorsObject->Grades->completed = true;
            $errorsObject->Grades->message = 'Grades Found';
            if(in_array($routeName,$errorsObject->Skills->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('marks.status',['errorObject'=>$errorsObject]);
            }
        }
        elseif (count(Remarks::where('school_id',Qs::findActiveSchool()[0]->id)->get()) < 2) {
            $errorsObject->Subjects->completed = true;
            $errorsObject->Subjects->message = 'Subjects Found';
            $errorsObject->Preferences->completed = true;
            $errorsObject->Preferences->message = 'Marking Period Preferences Set';
            $errorsObject->Grades->completed = true;
            $errorsObject->Grades->message = 'Grades Found';
            $errorsObject->Skills->completed = true;
            $errorsObject->Skills->message = 'skills Found';
            if(in_array($routeName,$errorsObject->comments->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('marks.status',['errorObject'=>$errorsObject]);
            }
        }
        else{
            $errorsObject->Subjects->completed = true;
            $errorsObject->Preferences->completed = true;
            $errorsObject->Grades->completed = true;
            $errorsObject->Skills->completed = true;
            $errorsObject->comments->completed = true;
            $errorsObject->Subjects->message = 'Subjects Found';
            $errorsObject->Preferences->message = 'Marking Period Preferences Set';
            $errorsObject->Grades->message = 'Grades Found';
            $errorsObject->Skills->message = 'Skills Found';
            $errorsObject->comments->message = 'Comments Found';
            return $next($request);
        }

    }
}
