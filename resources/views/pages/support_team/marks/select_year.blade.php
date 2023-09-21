@extends('layouts.master')
@section('page_title', 'Select Exam Year')
@section('content')
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h5 class="card-title"><i class="icon-alarm mr-2"></i> Select Exam Year</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form method="post" action="{{ route('marks.year_select', $student_id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="year" class="font-weight-bold col-form-label-lg">Select Exam Year:</label>
                            <select required id="year" name="year" data-placeholder="Select Exam Year" class="form-control select select-lg">
                                @foreach($years as $y)
                                    <option value="{{ $y->acad_year_id }}">{{ Qs::findAcademicYearById($y->acad_year_id)[0]->title  }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-primary btn-lg">Submit <i class="icon-paperplane ml-2"></i></button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
