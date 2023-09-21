<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Resource_types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Qs;
use Storage;

class ResourceController extends Controller
{
    //

    public function index()
    {
        $resourceTypes = Resource_types::all();
        return view('pages.support_team.resources.index',['resourceType'=>$resourceTypes]);
    }

    public function create(Request $req)
    {
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
        Resource::create($data);
        return response()->json(["msg"=>"successfully created Resources"]);
    }
    public function all(Request $req){
        // return all resources
        $data = Resource::where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)->where('school_id',Qs::findActiveSchool()[0]->id)->get();
        return response()->json($data);
    }
    public function resource_by_id(Request $req,$resource_id){
        $data = Resource::where('resource_type_id',$resource_id)->where('acad_year_id',Qs::getActiveAcademicYear()[0]->id)->where('school_id',Qs::findActiveSchool()[0]->id)->get();
        return response()->json($data);
    }
    public function delete($resource_id){
        Resource::destroy($resource_id);
        return response('ok',200);
    }
    public function findResourcesByOne($resource_id){
        $data = Resource::where('id',$resource_id)->first();
        return response()->json($data);
    }

    public function editResourceById(Request $req,$resource_id){
        $data  = $req->all();
        Resource::where('id',$resource_id)->update($data)
            ->save();
        return response('ok',200);
    }
}
