@extends('layouts.master')
@section('page_title', 'Manage Marks')
@section('content')
@php
    $mp = Mk::getMarkPreference(Mk::getExamById($exam_id)[0]->marking_period_id)[0]
@endphp
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h6 class="card-title font-weight-bold">Fill The Form To Manage Marks</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @include('pages.support_team.marks.selector')
        </div>
    </div>
    <div class="alert alert-warning alert-dismissible fade show" role="alert" {{Mk::isMarkingPeriod($mp->marking_period_id)?'hidden':''}}>
        <strong>Oops!</strong> The Marking Period is currently Locked. Contact the Administrator.
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card shadow-none">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4"><h6 class="card-title"><strong>Subject: </strong> {{ $m->subject->name }}</h6></div>
                <div class="col-md-4"><h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->title.' '.$m->section->name }}</h6></div>
                <div class="col-md-4"><h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name.' - '.$m->acad_year->title }}</h6></div>
            </div>
        </div>

        <div class="card-body">
            @include('pages.support_team.marks.edit')
            {{--@include('pages.support_team.marks.random')--}}
        </div>
    </div>

    {{--Marks Manage End--}}

@endsection
