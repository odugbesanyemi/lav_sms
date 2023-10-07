@extends('layouts.master')
@section('page_title', 'Manage Exam Marks')
@section('content')
    <div class="card shadow-none">
        <div class="card-header bg-purple-100 header-elements-inline py-3  text-secondary">
            <h5 class="card-title text-purple-900"><i class="icon-books mr-2 text-purple-800"></i> Manage Exam Marks</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @include('pages.support_team.marks.selector')
        </div>
    </div>
    @endsection
