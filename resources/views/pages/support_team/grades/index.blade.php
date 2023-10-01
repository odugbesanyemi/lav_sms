@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

        <div class="">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-grades" class="nav-link active" data-toggle="tab">Manage Grades</a></li>
                <li class="nav-item"><a href="#new-grade" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Add Grade</a></li>
            </ul>

            <div class="tab-content p-md-4 border-l border-r border-b bg-white">
                    <div class="tab-pane fade show active" id="all-grades">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Range</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($grades as $gr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $gr->name }}</td>
                                    <td>{{ $gr->mark_from.' - '.$gr->mark_to }}</td>
                                    <td>{{ $gr->remark }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if(Qs::userIsTeamSA())
                                                    {{--Edit--}}
                                                    <a href="{{ route('grades.edit', $gr->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                   @endif
                                                    @if(Qs::userIsSuperAdmin())
                                                    {{--Delete--}}
                                                    <a id="{{ $gr->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $gr->id }}" action="{{ route('grades.destroy', $gr->id) }}" class="hidden">@csrf @method('delete')</form>
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

                <div class="tab-pane fade" id="new-grade">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                                <span>If The grade you are creating applies to all class types </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('grades.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="{{ old('name') }}" required type="text" class="form-control text-uppercase" placeholder="E.g. A1">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Mark From <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input min="0" max="100" name="mark_from" value="{{ old('mark_from') }}" required type="number" class="form-control" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Mark To <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input min="0" max="100" name="mark_to" value="{{ old('mark_to') }}" required type="number" class="form-control" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Remark</label>
                                    <div class="col-lg-9">
                                        <select class="form-control select" name="remark" id="remark">
                                            <option value="">Select Remark...</option>
                                            @foreach(Mk::getRemarks() as $rem)
                                                <option {{ old('remark') == $rem ? 'selected' : '' }} value="{{ $rem }}">{{ $rem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="school_id" value="{{Qs::findActiveSchool()[0]->id}}">

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{--Class List Ends--}}

@endsection
