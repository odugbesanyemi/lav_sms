@extends('layouts.master')
@section('page_title', 'Manage Timetables')
@section('content')

<div class="">
    <ul class="nav nav-tabs nav-tabs-highlight">
        @if(Qs::userIsTeamSA())
        <li class="nav-item"><a href="#add-tt" class="nav-link active" data-toggle="tab">Create Timetable</a></li>
        @endif
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Show TimeTables</a>
            <div class="dropdown-menu dropdown-menu-right">
                @foreach($my_classes as $mc)
                    <a href="#ttr{{ $mc->id }}" onclick="getData('{{$mc->id}}')" class="dropdown-item" data-toggle="tab">{{ $mc->title }}</a>
                @endforeach
            </div>
        </li>
    </ul>


    <div class="tab-content">

        @if(Qs::userIsTeamSA())
        <div class="tab-pane fade show active border-l border-b border-r s" id="add-tt">

            <div class="col-md-12 py-3">
                <form class="ajax-store" method="post" action="{{ route('ttr.store') }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of TimeTable">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Marking Period <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select required data-placeholder="Select Marking Period" class="form-control select" name="marking_period_id" id="marking_period_id">
                                @foreach($markingPeriods as $mp)
                                    <option {{ old('marking_period_id') == $mp->id ? 'selected' : '' }} value="{{ $mp->id }}">{{ $mp->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Class <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select required data-placeholder="Select Class" class="form-control select" name="my_class_id" id="my_class_id">
                                @foreach($my_classes as $mc)
                                    <option {{ old('my_class_id') == $mc->id ? 'selected' : '' }} value="{{ $mc->id }}">{{ $mc->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="exam_id" class="col-lg-3 col-form-label font-weight-semibold">Type (Class or Exam)</label>
                        <div class="col-lg-9">
                            <select class="select form-control" name="exam_id" id="exam_id">
                                <option value="">Class Timetable</option>
                                @foreach($exams as $ex)
                                    <option {{ old('exam_id') == $ex->id ? 'selected' : '' }} value="{{ $ex->id }}">{{ $ex->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="school_id" value="{{Qs::findActiveSchool()[0]->id}}">
                    <input type="hidden" name="acad_year_id" value="{{Qs::getActiveAcademicYear()[0]->id}}">

                    <div class="text-right">
                        <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>
            </div>

        </div>
        @endif

        @foreach($my_classes as $mc)
            <div class="tab-pane fade thisOne" id="ttr{{ $mc->id }}">
                <div class="search py-3">
                    <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <i class="fi fi-rr-search text-xl text-slate-300 flex"></i>
                            </div>
                            <input oninput="searchData('{{$mc->id}}')" type="text" id="ttrSearch{{$mc->id}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quick Search..." required>
                        </div>
                    </form>
                </div>
                <div class="w-full grid gap-2 grid-cols-1" id="data-container-{{$mc->id}}" >
                    <span class="py-3 px-3 border-t border-b bg-gray-50 animate-pulse transition-all flex items-center justify-between">
                        <span class="block h-10 w-10 rounded-full bg-gray-100 border"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border max-md:hidden"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border max-md:hidden"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border"></span>
                    </span>
                    <span class="py-3 px-3 border-t border-b bg-gray-50 animate-pulse transition-all flex items-center justify-between">
                        <span class="block h-10 w-10 rounded-full bg-gray-100 border"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border max-md:hidden"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border max-md:hidden"></span>
                        <span class="block h-2 w-20 rounded-full bg-gray-100 border"></span>
                    </span>
                </div>
            </div>
        @endforeach

    </div>
</div>

<script>

$(document).ready()
{
    const initialTTRecords = {!! $tt_records->toJson() !!};
    function searchData(class_id){
        const searchInput = $('#ttrSearch'+class_id)
        const searchTerm = $('#ttrSearch'+class_id).val().toLowerCase();
        // Filter records based on the search
        const filteredTTRecords = initialTTRecords
        .filter((ttr)=>{
            return ttr.my_class_id == class_id
        })
        .filter(function (ttr) {
            return ttr.name.toLowerCase().includes(searchTerm); // Replace "someProperty" with the property you want to search
        });

        // Update the displayed records
        display(filteredTTRecords,class_id);
    }

    function display(records,id){
        $('#data-container-'+id).empty(); // Clear previous results
        // filter records based on id
        records
        .filter((item)=>{
            return item.my_class_id == id
        })
        .forEach(function(ttr){
            $('#data-container-'+id).append(`
                <div class="flex items-center justify-between p-2 py-3 border-t border-b bg-gray-50 hover:bg-white transition-all hover:shadow-sm">
                    <span class="flex items-center gap-2">
                        <div class="block h-10 w-10 rounded-full border bg-blue-50 flex items-center justify-center">
                            <i class="fi fi-tr-calendar-lines text-xl text-blue-700 flex"></i>
                        </div>
                        <div class="flex-col flex">
                            <span class="font-medium text-blue-700">${ttr.name}</span>
                            <span class="text-xs"> ${ttr.exam_id ? ttr.exam.name : 'class Timetable'}</span>
                        </div>
                    </span>
                    <span class="rounded-sm p-2 text-gray-900 flex items-center max-md:hidden">${ttr.my_class.title}</span>
                    <span class="rounded-sm p-2 text-gray-900 flex items-center max-md:hidden">${ttr.marking_period.title}</span>
                    <span class="rounded-sm p-2 text-gray-900 flex items-center">${ttr.acad_year.title}</span>
                    <span class="rounded-sm p-2 text-gray-900 flex items-center relative">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                {{--View--}}
                                <a href="timetables/records/show/${ttr.id}" class="dropdown-item"><i class="icon-eye"></i> View</a>

                                @if(Qs::userIsTeamSA())
                                {{--Manage--}}
                                <a href="timetables/records/manage/${ttr.id}" class="dropdown-item"><i class="icon-plus-circle2"></i> Manage</a>
                                {{--Edit--}}
                                <a href="timetables/records/edit/${ttr.id}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                @endif

                                {{--Delete--}}
                                @if(Qs::userIsSuperAdmin())
                                    <a id="${ttr.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                    <form method="post" id="item-delete-${ttr.id}" action="timetables/records/delete/${ttr.id}" class="hidden">@csrf @method('delete')</form>
                                @endif
                            </div>
                        </div>
                    </span>
                </div>
            `);
        });
    };
    function getData(id){
        display(initialTTRecords,id)
    }
    // Call the display function to initially display the records
};
</script>



@endsection
