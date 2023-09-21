@extends('layouts.master')
@section('page_title', 'Manage TimeTables')
@section('content')
<div class="">
    <div class="card shadow-none">
        <div class="card-header header-elements-inline bg-body-tertiary text-dark py-3">
            <h6 class="card-title">Manage TimeTables</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                @if(Qs::userIsTeamSA())
                <li class="nav-item"><a href="#add-tt" class="nav-link active" data-toggle="tab">Create Timetable</a></li>
                @endif
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Show TimeTables</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach($my_classes as $mc)
                            <a href="#ttr{{ $mc->id }}" class="dropdown-item" data-toggle="tab">{{ $mc->title }}</a>
                        @endforeach
                    </div>
                </li>
            </ul>


            <div class="tab-content">

                @if(Qs::userIsTeamSA())
                <div class="tab-pane fade show active" id="add-tt">
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
                    <div class="tab-pane fade" id="ttr{{ $mc->id }}">
                        <div class="overflow-x-auto w-full">
                            <table class="dtable table table-auto w-full ">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Period</th>
                                        <th>Type</th>
                                        <th>Year</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                @foreach($tt_records->where('my_class_id', $mc->id) as $ttr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ttr->name }}</td>
                                        <td>{{ $ttr->my_class->title }}</td>
                                        <td>{{ $ttr->marking_period->title }}</td>
                                        <td>{{ ($ttr->exam_id) ? $ttr->exam->name : 'Class TimeTable' }}
                                        <td>{{ $ttr->acad_year->title }}</td>
                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        {{--View--}}
                                                        <a href="{{ route('ttr.show', $ttr->id) }}" class="dropdown-item"><i class="icon-eye"></i> View</a>

                                                        @if(Qs::userIsTeamSA())
                                                        {{--Manage--}}
                                                        <a href="{{ route('ttr.manage', $ttr->id) }}" class="dropdown-item"><i class="icon-plus-circle2"></i> Manage</a>
                                                        {{--Edit--}}
                                                        <a href="{{ route('ttr.edit', $ttr->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                        @endif

                                                        {{--Delete--}}
                                                        @if(Qs::userIsSuperAdmin())
                                                            <a id="{{ $ttr->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                            <form method="post" id="item-delete-{{ $ttr->id }}" action="{{ route('ttr.destroy', $ttr->id) }}" class="hidden">@csrf @method('delete')</form>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>


    {{--TimeTable Ends--}}

@endsection
