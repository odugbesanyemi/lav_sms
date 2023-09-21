@extends('layouts.master')
@section('page_title', 'Student Marksheet')
@section('content')
<?php

    // get average result
    function getAverage($markingOrder,$sr,$year,$sub,$ex){
        $sum= 0;
        $count = 0;
        // Loop using $type_order (assuming it's an array or iterable)
        for ($i = 0; $i < $markingOrder; $i++) {
            // Call the function to get the mark total
            $markTotal = Mk::getMarkTotal($sr->user->id, $year, $sub->id, $ex->marking_period->mp_type, $i + 1);
            // Check if the markTotal is not null (or any other appropriate condition)
            if ($markTotal !== null) {
                // Accumulate the sum
                $sum += $markTotal;
                $count++;
            }
        }

        // Calculate the average
        if ($count > 0) {
            $average = $sum / $count;
            return $average;
        } else {
            // Handle the case where $count is zero to avoid division by zero
            $average = 0;
            return 0;
        }          
    }
?>
    <div class="card shadow-none border-none">
        <div class="card-header text-center bg-secondary text-light py-3">
            <h4 class="card-title font-weight-bold">Student Marksheet for =>  {{ $sr->user->name.' ('.$my_class->title.' '.$sr->section->name.')' }} </h4>
        </div>
    </div>

    @foreach($exams as $ex)
    @php
        $markPreference = Mk::getMarkPreference($ex->marking_period_id)[0];
        $isQuarter = Mk::markingTypeIsQuarter($ex->marking_period_id);
        $isSemester = Mk::markingTypeIsSemester($ex->marking_period_id);
        $markingOrder = Mk::markingTypeOrder($ex->marking_period_id);
        if ($isQuarter && $markingOrder == 1||$isSemester && $markingOrder == 1) {
            $colspan = 3;
        } elseif ($isQuarter && $markingOrder == 2 || $isSemester && $markingOrder == 2) {
            $colspan = 4;
        } else {
            $colspan = 5;
        }

        if($isQuarter && $markingOrder ==1){
            $currentTermName = 'MID TERM';
        }elseif($isQuarter && $markingOrder == 2){
            $currentTermName = "FINAL TERM";
        }elseif($isSemester && $markingOrder == 1){
            $currentTermName = "1ST TERM";
        }elseif($isSemester && $markingOrder == 2){
            $currentTermName = "2ND TERM";
        }else{
            $currentTermName = "3RD TERM";
        }    
    @endphp
        @foreach($exam_records->where('exam_id', $ex->id) as $exr)
            <div class="card-body py-4 border rounded-2 p-md-4 mb-3">
                <div class="card shadow-none">
                    <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
                        <h6 class="font-weight-bold">{{ $ex->name.' - '.$ex->acad_year->title }}</h6>
                        {!! Qs::getPanelOptions() !!}
                    </div>

                    <div class="card-body collapse">

                        {{--Sheet Table--}}
                        @include('pages.support_team.marks.show.sheet') 
                        {{--Print Button--}}
                        <div class="text-center mt-3">
                            <a target="_blank" href="{{ route('marks.print', [Qs::hash($student_id), $ex->id, $year]) }}" class="btn btn-secondary btn-lg">Print Marksheet <i class="icon-printer ml-2"></i></a>
                        </div>

                    </div>
                </div>

            {{--    EXAM COMMENTS   --}}
            @include('pages.support_team.marks.show.comments')

            {{-- SKILL RATING --}}
            @include('pages.support_team.marks.show.skills')

            </div>

        @endforeach
    @endforeach

@endsection
