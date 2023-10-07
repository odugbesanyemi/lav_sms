@extends('layouts.master')
@section('page_title', 'Manage Marks')
@section('content')
@php
    $mp = Mk::getMarkPreference(Mk::getExamById($exam_id)[0]->marking_period_id)[0]
@endphp
<div class="space-y-3">
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
        <div class="bg-body-tertiary card-header">
            <div class="grid md:grid-cols-3 gap-2">
                <div class=""><h6 class="card-title"><strong>Subject: </strong> {{ $m->subject->name }}</h6></div>
                <div class=""><h6 class="card-title"><strong>Class: </strong> {{ $m->my_class->title.' '.$m->section->name }}</h6></div>
                <div class=""><h6 class="card-title"><strong>Exam: </strong> {{ $m->exam->name.' - '.$m->acad_year->title }}</h6></div>
            </div>
        </div>

        <div class="card-body overflow-x-auto">
            @include('pages.support_team.marks.edit')
            {{--@include('pages.support_team.marks.random')--}}
        </div>
    </div>    
</div>


    {{--Marks Manage End--}}
    <script>
        $(document).ready()
        {
            // for updating records
            var records = {!! $marks->toJson() !!};
            records = Object.values(records);
            function searchData(class_id){
                const searchInput = $('#dataSearch'+class_id)
                const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
                // Filter records based on the search
                const filteredRecords = records
                .filter((data)=>{
                    return class_id==0?data:data.my_class_id == class_id
                })
                .filter(function (data) {
                    return data.user.name.toLowerCase().includes(searchTerm) ; // Replace "someProperty" with the property you want to search
                });
    
                // Update the displayed records
                display(filteredRecords,class_id);
            }
            function display(records,id){
    
                $('#data-container-'+id).empty(); // Clear previous results
                // filter records based on id
                records
                .filter((item)=>{
                    return id==0?item:item.my_class_id == id
                })
                .forEach(function(data,index){
                    $('#data-container-'+id).append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td class="whitespace-nowrap">${data.user.name} </td>
                            <td>${data.user.student_record.adm_no}</td>

                            {{-- CA AND EXAMS --}}
                            <td><input  {{Mk::isMarkingPeriod('${data.marking_period_id}')?'':'disabled'}} title="1ST CA" min="1" max="${data.ca_final_score?data.ca_final_score:40}" class="text-center form-input" name="ca_score_${data.id}" value="${data.ca_score}" type="number"></td>
                            <td><input {{Mk::isMarkingPeriod('${data.marking_period_id}')?'':'disabled'}} title="EXAM" min="1" max=" ${data.exam_final_score?data.exam_final_score:60}" class="text-center form-input" name="exam_score_${data.id}" value="${data.exam_score}" type="number"></td>
                        </tr>
                    `);
                });
            };
            function getData(id=null){
                if(id==null){
                    id=0;
                }
                display(records,id)
            }
            getData();
        }
    </script>
@endsection
