<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Resource_types;
use App\Models\Subject;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Qs;
use Storage;

class ResourceController extends Controller
{
    //

    public function index()
    {
        $subjects = [];
        if(Qs::userIsTeacher()){
            // get the subject and class_id
            $subjects = Qs::findTeacherSubjects(Auth::user()->id);
        }elseif (Qs::userIsTeamSA()) {
            $subjects= Subject::all();
        }
        $resourceTypes = Resource_types::all();
        return view('pages.support_team.resources.index',['resourceType'=>$resourceTypes,'subjects'=>$subjects]);
    }

    public function create(Request $req)
    {
        $data = $req->only(['title','resource_type_id','description','school_id','acad_year_id','subject_id']);
        if(Qs::userIsTeacher()){
            // get the subject and class_id
        }

        $filename = $req->file('filename');
        $image = $req->file('image');
        if($filename){
            $resource_filename = time().'resource_'.$data['resource_type_id'].'.'.$filename->getClientOriginalExtension();
            Storage::disk('public')->put('resources/'.$resource_filename,file_get_contents($filename));
            $data['filename']=$resource_filename;
        }
        if($image){
            $image_name = time().'resource_'.$data['title'].'.'.$image->getClientOriginalExtension();
            Storage::disk('public')->put('images/'.$image_name,file_get_contents($image));
            $data['image']=$image_name;
        }
        $data['added_by']=Auth::user()->id;
        Resource::create($data);
        return response()->json(["msg"=>"successfully created Resources"]);
    }
    public function all(Request $req){
        // return all resources

        if(Qs::userIsTeamSA()){
            $data = Resource::where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
            ->where('school_id',Qs::findActiveSchool()[0]->id)
            ->get()
            ->map(function($item){
                $item->author = Qs::userInfo($item->added_by)->name;
                return $item;
             });
        }elseif (Qs::userIsTeacher()) {
            # code...
            $subjects = Qs::findTeacherSubjects(Auth::user()->id)->pluck('id')->toArray();
            $data = Resource::where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
             ->where('school_id',Qs::findActiveSchool()[0]->id)
             ->whereIn('subject_id',$subjects)
             ->get()
             ->map(function($item){
                $item->author = Qs::userInfo($item->added_by)->name;
                return $item;
             });
        }elseif (Qs::userIsStudent()) {
            # code... show only resouces where the class_id equals student class
            $data = Resource::where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
                ->where('school_id',Qs::findActiveSchool()[0]->id)
                ->get()
                ->map(function($item){
                    $item->author = Qs::userInfo($item->added_by)->name;
                    return $item;
                 })
                ->filter(function($item){
                    $class_id = Qs::findStudentRecord(Auth::user()->id)->my_class_id;
                    $subject_id = $item['subject_id'];
                    if($subject_id == 0)return;
                    $subject_class= Subject::where('id',$subject_id)->first()->my_class_id;
                    if ($class_id == $subject_class){
                        return $item;
                    }else{
                        return false;
                    }
                })
                ->values()
                ->toArray();
        }else{
            // user is parent
            $data = Resource::where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
                ->where('school_id',Qs::findActiveSchool()[0]->id)
                ->get()
                ->map(function($item){
                    $item->author = Qs::userInfo($item->added_by)->name;
                    return $item;
                 })
                ->filter(function($item){
                    $child_class = Qs::findMyChildren(Auth::user()->id)->pluck('my_class_id')->toArray();
                    $subject_id = $item['subject_id'];
                    if($subject_id == 0)return;
                    $subject_class= Subject::where('id',$subject_id)->first()->my_class_id;
                    if (in_array($subject_class,$child_class)){
                        return $item;
                    }else{
                        return false;
                    }
                })
                ->values()
                ->toArray();
        }

        return response()->json($data);
    }
    public function resource_by_id(Request $req,$resource_id){
        if(Qs::userIsTeamSA()){
            $data = Resource::where('resource_type_id',$resource_id)
                ->where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
                ->where('school_id',Qs::findActiveSchool()[0]->id)
                ->get()
                ->map(function($item){
                    $item->author = Qs::userInfo($item->added_by)->name;
                    return $item;
                 });
        }elseif (Qs::userIsTeacher()) {
            # code...
            $subjects = Qs::findTeacherSubjects(Auth::user()->id)->pluck('id')->toArray();
            $data = Resource::where('resource_type_id',$resource_id)
             ->where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
             ->where('school_id',Qs::findActiveSchool()[0]->id)
             ->whereIn('subject_id',$subjects)
             ->get()
             ->map(function($item){
                $item->author = Qs::userInfo($item->added_by)->name;
                return $item;
             });
        }elseif (Qs::userIsStudent()) {
            # code... show only resouces where the class_id equals student class
            $data = Resource::where('resource_type_id',$resource_id)
                ->where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
                ->where('school_id',Qs::findActiveSchool()[0]->id)
                ->get()
                ->map(function($item){
                    $item->author = Qs::userInfo($item->added_by)->name;
                    return $item;
                 })
                ->filter(function($item){
                    $class_id = Qs::findStudentRecord(Auth::user()->id)->my_class_id;
                    $subject_id = $item['subject_id'];
                    if($subject_id == 0)return;
                    $subject_class= Subject::where('id',$subject_id)->first()->my_class_id;
                    if ($class_id == $subject_class){
                        return $item;
                    }else{
                        return false;
                    }
                })
                ->values()
                ->toArray();
        }else{
            // user is parent
            $data = Resource::where('resource_type_id',$resource_id)
                ->where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)
                ->where('school_id',Qs::findActiveSchool()[0]->id)
                ->get()
                ->map(function($item){
                    $item->author = Qs::userInfo($item->added_by)->name;
                    return $item;
                 })
                ->filter(function($item){
                    $child_class = Qs::findMyChildren(Auth::user()->id)->pluck('my_class_id')->toArray();
                    $subject_id = $item['subject_id'];
                    if($subject_id == 0)return;
                    $subject_class= Subject::where('id',$subject_id)->first()->my_class_id;
                    if (in_array($subject_class,$child_class)){
                        return $item;
                    }else{
                        return false;
                    }
                })
                ->values()
                ->toArray();
        }
        return response()->json($data);
    }
    public function delete($resource_id){
        $subjects = [];
        if(Qs::userIsTeacher()){
            // get the subject and class_id
            $subjects = Qs::findTeacherSubjects(Auth::user()->id);
        }elseif (Qs::userIsTeamSA()) {
            $subjects= Subject::all();
        }
        $resourceTypes = Resource_types::all();
        Resource::destroy($resource_id);
        return redirect()->route('resource.index',['resourceType'=>$resourceTypes,'subjects'=>$subjects]);
    }
    public function edit($resource_id){
        $subjects = [];
        if(Qs::userIsTeacher()){
            // get the subject and class_id
            $subjects = Qs::findTeacherSubjects(Auth::user()->id);
        }elseif (Qs::userIsTeamSA()) {
            $subjects= Subject::all();
        }
        $resourceTypes = Resource_types::all();
        $data = Resource::where('id',$resource_id)->first();
        return view('pages.support_team.resources.edit',['resource'=>$data,'resourceType'=>$resourceTypes,'subjects'=>$subjects]);
    }

    public function update(Request $req,$resource_id){
        $subjects = [];
        if(Qs::userIsTeacher()){
            // get the subject and class_id
            $subjects = Qs::findTeacherSubjects(Auth::user()->id);
        }elseif (Qs::userIsTeamSA()) {
            $subjects= Subject::all();
        }
        $resourceTypes = Resource_types::all();
        $data = $req->only(['title','resource_type_id','description','school_id','acad_year_id']);
        $filename = $req->file('filename');
        $image = $req->file('image');
        if($filename){
            $resource_filename = time().'resource_'.$data['resource_type_id'].'.'.$filename->getClientOriginalExtension();
            Storage::disk('public')->put('resources/'.$resource_filename,file_get_contents($filename));
            $data['filename']=$resource_filename;
        }
        if($image){
            $image_name = time().'resource_'.$data['title'].'.'.$image->getClientOriginalExtension();
            Storage::disk('public')->put('images/'.$image_name,file_get_contents($image));
            $data['image']=$image_name;
        }
        Resource::where('id',$resource_id)->update($data);
        return redirect()->route('resource.index',['resourceType'=>$resourceTypes,'subjects'=>$subjects]);
    }
}
