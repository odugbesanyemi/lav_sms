@extends('layouts.master')
@section('page_title', 'Manage Exam Marks')
@section('content')
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Manage Exam Marks</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @include('pages.support_team.marks.selector')
        </div>
    </div>
    @endsection
