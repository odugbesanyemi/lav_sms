<?php

namespace App\Http\Middleware\Custom;

use App\Models\GradeLevels;
use App\Models\Section;
use Closure;
use Illuminate\Http\Request;
use Qs;
use Route;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;

class SetupStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $studentRepo,$userRepo;
    public function __construct(studentRepo $studentRepo,UserRepo $userRepo)
    {
        $this->studentRepo= $studentRepo;
        $this->userRepo = $userRepo;
    }
    public function handle(Request $request, Closure $next)
    {
        // purpose of this middleware is to check if any of the following
        $errors = [
            'schools'=>['completed'=>false,'message'=>'You have not created a school as yet. Kindly create one to Manage','route'=>['setup.schools.create']],
            'calendar'=>['completed'=>false, 'message'=>'This school has no active Calendar. Create A calendar now to continue.','route'=>['setup.calendar','setup.calendar.save-academic-year']],
            'Classes'=>['completed'=>false,'message'=>'There are no Classes for current Selected School. Create one Now to continue.','route'=>['setup.grade-levels','setup.grade-levels-add']],
            'Sections'=>['completed'=>false,'message'=>'One or few  Classes might be missing a section. Create one Now to continue.','route'=>['sections.index','sections.store']],
            'Terms'=>['completed'=>false,'message'=>'There are no marking periods e.g Terms for current Selected School. Create one Now to continue.','route'=>['setup.marking-period','setup.marking-periods-new']],
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
        $allClasses = GradeLevels::where('school_id',Qs::findActiveSchool()[0]->id)->get()->pluck('id')->toArray();
        $allSections = Section::select('school_id','my_class_id')
            ->where('school_id', Qs::findActiveSchool()[0]->id)
            ->distinct('my_class_id')
            ->get()
            ->pluck('my_class_id')
            ->toArray();

        $isClassSections = ($allSections==$allClasses);
        // schools is more than 1

        if(count(Qs::findActiveSchool())==0){
            // meaning there are no registered schools in the app
            return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
        }
        // calendar is more than 1
        elseif (Qs::getActiveAcademicYear()[0]==null ) {
            $errorsObject->schools->completed = true;
            $errorsObject->schools->message = 'Schools Already Created Successfully';
            if(in_array($routeName,$errorsObject->calendar->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
            }
        }
        // Grade Levels for current school is more than minimum of 1
        elseif(count(Qs::getSchoolGradeLevels())==0){
            // no classes
            $errorsObject->schools->completed = true;
            $errorsObject->calendar->completed = true;
            $errorsObject->schools->message = 'Schools Found';
            $errorsObject->calendar->message = 'Calendar Found';
            if(in_array($routeName,$errorsObject->Classes->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
            }
        }
        elseif (!$isClassSections){
            $errorsObject->schools->completed = true;
            $errorsObject->calendar->completed = true;
            $errorsObject->Classes->completed = true;
            $errorsObject->schools->message = 'Schools Found';
            $errorsObject->calendar->message = 'Calendar Found';
            $errorsObject->Classes->message = 'Class Found';
            if(in_array($routeName,$errorsObject->Sections->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
            }
        }        
        // marking Periods is more than 1
        elseif (count(Qs::getSemesters())==0){
            $errorsObject->schools->completed = true;
            $errorsObject->calendar->completed = true;
            $errorsObject->Classes->completed = true;
            $errorsObject->Sections->completed = true;
            $errorsObject->schools->message = 'Schools Found';
            $errorsObject->calendar->message = 'Calendar Found';
            $errorsObject->Classes->message = 'Class Found';
            $errorsObject->Sections->message = 'Sections Found';
            if(in_array($routeName,$errorsObject->Terms->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
                return $next($request);
            }else{
                return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
            }
        }

        // students for current school is more than
        // elseif(count($this->studentRepo->activeStudents()->get())==0){
        //     $errorsObject->schools->completed = true;
        //     $errorsObject->calendar->completed = true;
        //     $errorsObject->Classes->completed = true;
        //     $errorsObject->Terms->completed = true;
        //     $errorsObject->schools->message = 'Schools Found';
        //     $errorsObject->calendar->message = 'Calendar Found';
        //     $errorsObject->Classes->message = 'Class Found';
        //     $errorsObject->Terms->message = 'Term Found';
        //     if(in_array($routeName,$errorsObject->students->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
        //         return $next($request);
        //     }else{
        //         return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
        //     }
        // }
        // // teachers for current school is more than 1
        // elseif(count($this->userRepo->getUserByType('teacher'))==0){
        //     $errorsObject->schools->completed = true;
        //     $errorsObject->calendar->completed = true;
        //     $errorsObject->Classes->completed = true;
        //     $errorsObject->Terms->completed = true;
        //     $errorsObject->students->completed = true;
        //     $errorsObject->schools->message = 'Schools Found';
        //     $errorsObject->calendar->message = 'Calendar Found';
        //     $errorsObject->Classes->message = 'Class Found';
        //     $errorsObject->Terms->message = 'Term Found';
        //     $errorsObject->students->message = 'student Found';
        //     if(in_array($routeName,$errorsObject->teachers->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
        //         return $next($request);
        //     }else{
        //         return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
        //     }
        // }
        // // parents for current school is more than 1
        // elseif(count($this->userRepo->getUserByType('parent'))==0){
        //     $errorsObject->schools->completed = true;
        //     $errorsObject->calendar->completed = true;
        //     $errorsObject->Classes->completed = true;
        //     $errorsObject->Terms->completed = true;
        //     $errorsObject->students->completed = true;
        //     $errorsObject->teachers->completed = true;
        //     $errorsObject->schools->message = 'Schools Found';
        //     $errorsObject->calendar->message = 'Calendar Found';
        //     $errorsObject->Classes->message = 'Class Found';
        //     $errorsObject->Terms->message = 'Term Found';
        //     $errorsObject->students->message = 'student Found';
        //     $errorsObject->teachers->message = 'teacher Found';
        //     if(in_array($routeName,$errorsObject->parent->route) || checkSelected($errorsObject,$routeName)&&checkSelected($errorsObject,$routeName)->completed==true){
        //         return $next($request);
        //     }else{
        //         return redirect()->route('setup.status',['errorObject'=>$errorsObject]);
        //     }
        // }
        else{
            $errorsObject->schools->completed = true;
            $errorsObject->calendar->completed = true;
            $errorsObject->Classes->completed = true;
            $errorsObject->Sections->completed = true;
            $errorsObject->Terms->completed = true;
            $errorsObject->schools->message = 'Schools Found';
            $errorsObject->calendar->message = 'Calendar Found';
            $errorsObject->Classes->message = 'Class Found';
            $errorsObject->Sections->message = 'Sections Found';
            $errorsObject->Terms->message = 'Term Found';
            return $next($request);
        }

    }
}
