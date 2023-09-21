@extends('layouts.master')
@section('page_title', 'Edit Exam - '.$ex->name. ' ('.$ex->acad_year->title.')')
@section('content')

    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h6 class="card-title">Edit Exam</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <form method="post" action="{{ route('exams.update', $ex->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $ex->name }}" required type="text" class="form-control" placeholder="Name of Exam">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="term" class="col-lg-3 col-form-label font-weight-semibold">Marking Period</label>
                            <div class="col-lg-9">
                            <select data-placeholder="Select Teacher" class="form-control select-search" name="marking_period_id" id="marking_period_id">
                            @foreach ($markingPeriods as $mp )
                                <option {{ $ex->marking_period_id == $mp->id?'selected':'' }} value="{{ $mp->id }}">{{ $mp->title }}</option>
                            @endforeach
                            </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class Edit Ends--}}

@endsection
