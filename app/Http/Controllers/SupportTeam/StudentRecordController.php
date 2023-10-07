<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Http\Requests\Student\StudentRecordUpdate;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use App\Models\Classrooms;
use App\Models\GradeLevels;
use App\Models\Section;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader as CSVReader;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;
use Spatie\SimpleExcel\SimpleExcelReader;
use Request;

class StudentRecordController extends Controller
{
    protected $loc, $my_class, $user, $student;

   public function __construct(LocationRepo $loc, MyClassRepo $my_class, UserRepo $user, StudentRepo $student)
   {
       $this->middleware('teamSA', ['only' => ['edit','update', 'reset_pass', 'create', 'store', 'graduated'] ]);
       $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->loc = $loc;
        $this->my_class = $my_class;
        $this->user = $user;
        $this->student = $student;
   }

    public function reset_pass($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        $data['password'] = Hash::make('student');
        $this->user->update($st_id, $data);
        return back()->with('flash_success', __('msg.p_reset'));
    }

    public function create()
    {
        $data['my_classes'] = GradeLevels::where('school_id',Qs::findActiveSchool()[0]->id)->get();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        return view('pages.support_team.students.add', $data);
    }

    public function import($class_id=null)
    {
        $data['classes'] = Qs::getSchoolGradeLevels();
        if($class_id){
            $data['sections'] = Section::where('my_class_id',$class_id)->get();
            $data['selected'] = true;
            $data['class_id'] = $class_id;
        }
        return view('pages.support_team.students.import',$data);
    }
    public function class_selector(HttpRequest $req){
        return redirect()->route('students.import',['class_id'=>$req->input('class_id')]);
    }
    public function saveImportData(HttpRequest $req){
        // $data = $req->
        if($req->hasFile('filename')){
            $file = $req->file('filename');
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('temp',$filename);
            $fullpath=Storage::path($path);
            $reader=ReaderEntityFactory::createReaderFromFile($fullpath);
            $reader->open($fullpath);
            $isFirstRow = true;
            $csvRows=[];
            $headerRow =[];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    if($isFirstRow) {
                        $headerRow = $row->toArray();
                        $isFirstRow = false;
                    } else {
                        $dataRow = $row->toArray();
                        $rowData = array_combine($headerRow, $dataRow);
                        array_push($csvRows, $rowData);
                    }
                }
            }
            $reader->close();

            if(count($csvRows)){
                $data['student_records']=$csvRows;
            }
        }
        // debugbar()->log($req->student_record);
        if($req->student_record){
            $data['school_id']=$req->input('school_id');
            $data['acad_year_id']= $req->input('acad_year_id');
            $data['section_id']= $req->input('section_id')==null?0:$req->input('section_id');
            $data['class_id']= $req->input('class_id');
            $sr =  $req->only(Qs::getStudentData());
            $currentDate = Carbon::now();
            $student_data = json_decode($req->student_record);
            // create parent username
            foreach ($student_data as $value) {
                # code...
                $su['name'] = $value->lastName.' '.$value->firstName.' '.$value->otherNames;
                $pu['name'] = $value->lastName;
                $su['code'] = strtoupper(Str::random(10));
                $pu['code'] = strtoupper(Str::random(10));
                $su['username'] = $value->admNo;
                $su['dob'] = $value->dob;
                $pu['username'] = $value->parent_phone;
                $su['user_type'] = 'student';
                $pu['user_type'] = 'parent';
                $su['photo']= Qs::getDefaultUserImage();
                $pu['photo']= Qs::getDefaultUserImage();
                $su['phone'] = $value->parent_phone;
                $pu['phone'] = $value->parent_phone;
                $su['password'] = Hash::make('student');
                $pu['password'] = Hash::make('parent');
                $su['created_at']= time();
                $pu['created_at']= time();
                $su['updated_at'] = time();
                $pu['updated_at'] = time();

                $studentUser = $this->user->create($su);
                $parentUser = $this->user->create($pu);
                // student
                $birthdate = Carbon::parse($value->dob);
                $age = $birthdate->diffInYears($currentDate);
                $sr['adm_no'] = $value->admNo;
                $sr['user_id'] = $studentUser->id;
                $sr['session'] = Qs::getSetting('current_session');
                $sr['section_id']=$req->input('section_id')==null?Section::first()->id:$req->input('section_id');
                $sr['my_parent_id'] = $parentUser->id;
                $sr['my_class_id'] = $req->input('class_id');
                $sr['age']=$age;
                $this->student->createRecord($sr);
            }
            $data['msg']='ok';

        }

        return response()->json($data);
    }
    public function store(StudentRecordCreate $req)
    {
       $data =  $req->only(Qs::getUserRecord());
       $sr =  $req->only(Qs::getStudentData());
        $ct = $this->my_class->find($req->my_class_id)->short_name;

        $data['user_type'] = 'student';
        $data['name'] = ucwords($req->name);
        $data['code'] = strtoupper(Str::random(10));
        $data['password'] = Hash::make('student');
        $data['photo'] = Qs::getDefaultUserImage();
        $adm_no = $req->adm_no;
        $data['username'] = strtoupper(Qs::getAppCode().'/'.$ct.'/'.$sr['year_admitted'].'/'.($adm_no ?: mt_rand(1000, 99999)));

        if($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$data['code'], $f['name']);
            $data['photo'] = asset('storage/' . $f['path']);
        }

        $user = $this->user->create($data); // Create User

        $sr['adm_no'] = $data['username'];
        $sr['user_id'] = $user->id;
        $sr['session'] = Qs::getSetting('current_session');

        $this->student->createRecord($sr); // Create Student
        return Qs::jsonStoreOk();
    }

    public function listByClass($class_id)
    {
        $data['my_class'] = $mc = $this->my_class->getMC(['id' => $class_id])->first();
        $data['students'] = $this->student->findStudentsByClass($class_id);
        $data['sections'] = $this->my_class->getClassSections($class_id);

        return is_null($mc) ? Qs::goWithDanger() : view('pages.support_team.students.list', $data);
    }

    public function graduated()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['students'] = $this->student->allGradStudents();

        return view('pages.support_team.students.graduated', $data);
    }

    public function not_graduated($sr_id)
    {
        $d['grad'] = 0;
        $d['grad_date'] = NULL;
        $d['session'] = Qs::getSetting('current_session');
        $this->student->updateRecord($sr_id, $d);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function show($sr_id)
    {
        if(!$sr_id){return Qs::goWithDanger();}

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();

        /* Prevent Other Students/Parents from viewing Profile of others */
        if(Auth::user()->id != $data['sr']->user_id && !Qs::userIsTeamSAT() && !Qs::userIsMyChild($data['sr']->user_id, Auth::user()->id)){
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        return view('pages.support_team.students.show', $data);
    }

    public function edit($sr_id)
    {
        if(!$sr_id){return Qs::goWithDanger();}

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();
        $data['my_classes'] = $this->my_class->all();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        return view('pages.support_team.students.edit', $data);
    }

    public function update(StudentRecordUpdate $req, $sr_id)
    {
        if(!$sr_id){return Qs::goWithDanger();}

        $sr = $this->student->getRecord(['id' => $sr_id])->first();
        $d =  $req->only(Qs::getUserRecord());
        $d['name'] = ucwords($req->name);

        if($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$sr->user->code, $f['name']);
            $d['photo'] = asset('storage/' . $f['path']);
        }

        $this->user->update($sr->user->id, $d); // Update User Details

        $srec = $req->only(Qs::getStudentData());

        $this->student->updateRecord($sr_id, $srec); // Update St Rec

        /*** If Class/Section is Changed in Same Year, Delete Marks/ExamRecord of Previous Class/Section ****/
        Mk::deleteOldRecord($sr->user->id, $srec['my_class_id']);

        return Qs::jsonUpdateOk();
    }

    public function destroy($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        if(!$st_id){return Qs::goWithDanger();}

        $sr = $this->student->getRecord(['user_id' => $st_id])->first();
        $path = Qs::getUploadPath('student').$sr->user->code;
        Storage::exists($path) ? Storage::deleteDirectory($path) : false;
        $this->user->delete($sr->user->id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

}
