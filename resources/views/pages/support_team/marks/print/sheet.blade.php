{{--Exam Table--}}
<?php
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

<div class="sheet" id="resultInfo">
    <div class="d-flex border">
        <div class="w-100">
            <table align="center" class="table table-bordered align-middle text-center m-0 table-striped">
                <thead>
                    <tr class="">
                        <th colspan="9" class="fs-3 fw-bold p-2 table-light" style="background-color:#1b0c80; color:white;" >
                            COGNITIVE DOMAIN
                        </th>
                    </tr>
                    <tr class="align-middle">

                        <th rowspan="3" class="fw-bold fs-2">SUBJECTS</th>
                        @if ($isQuarter && $markingOrder > 1||$isSemester && $markingOrder > 1)
                        <th rowspan="2">{{ $isQuarter?'MID TERM':'1ST TERM' }}</th>
                        @endif
                        @if ($isQuarter && $markingOrder > 2 || $isSemester && $markingOrder > 2)
                        <th rowspan="2">{{ $isQuarter?'FINAL TERM':'2ND TERM' }}</th>
                        @endif

                        <th colspan="3">{{ $currentTermName}}</th>
                        <th rowspan="3">AVG.</th>
                        <th rowspan="3">GRADE</th>
                        <th rowspan="3">COMMENT</th>
                    </tr>
                    <tr>
                        <th>C.A.</th>
                        <th>EXAM</th>
                        <th>TOTAL</th>
                    </tr>
                    <tr>
                    @if ($isQuarter && $markingOrder > 1||$isSemester && $markingOrder > 1)
                        <th>100</th>
                    @endif
                    @if ($isQuarter && $markingOrder > 2 || $isSemester && $markingOrder > 2)
                        <th>100</th>
                    @endif

                        <th>{{ $markPreference->ca_final_score }}</th>
                        <th>{{ $markPreference->exam_final_score }}</th>
                        <th>{{ $markPreference->ca_final_score + $markPreference->exam_final_score }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalAve = 0;
                        $count = 0;
                    @endphp
                    @foreach ($subjects as $sub )
                    <tr class="align-start text-center">
                        <td class="text-start" style="font-weight: bold">{{ $sub->name }}</td>
                        @foreach($marks->where('subject_id', $sub->id)->where('exam_id', $ex->id) as $mk)
                            @if ($isQuarter && $markingOrder > 1||$isSemester && $markingOrder > 1)
                            <th >{{ Mk::getMarkTotal($sr->user->id,$year,$sub->id,$ex->marking_period->mp_type,1) }}</th>
                            @endif
                            @if ($isQuarter && $markingOrder > 2 || $isSemester && $markingOrder > 2)
                            <th>{{ Mk::getMarkTotal($sr->user->id,$year,$sub->id,$ex->marking_period->mp_type,2) }}</th>
                            @endif
                            <td>{{ $mk->ca_score }}</td>
                            <td>{{ $mk->exam_score }}</td>
                            <td>{{ $mk->ca_score + $mk->exam_score }}</td>
                            <td>{{ getAverage($markingOrder,$sr,$year,$sub,$ex) }}</td>
                            <td>{{ strtoupper(Mk::getGradeDetails($ex->school->id,getAverage($markingOrder,$sr,$year,$sub,$ex))->name) }}</td>
                            <td>{{ strtoupper(Mk::getGradeDetails($ex->school->id,getAverage($markingOrder,$sr,$year,$sub,$ex))->remark) }}</td>
                            @php
                                $totalAve +=getAverage($markingOrder,$sr,$year,$sub,$ex);
                                if(getAverage($markingOrder,$sr,$year,$sub,$ex) == NULL){
                                }else{
                                    $count++;
                                }
                            @endphp
                        @endforeach

                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="{{ $colspan }}"  class="fs-5 fw-semibold p-2 text-start">OVERALL AVERAGE  AND GRADE</td>
                        <td colspan="2" class="fw-bold fs-5">{{ round($totalAve / $count ) }}</td>
                        <td class="fw-bold fs-5">{{ strtoupper(Mk::getGradeDetails($ex->school->id,$totalAve / $count )->name) }}</td>
                        <td class="fs-5 fw-bold">{{ strtoupper(Mk::getGradeDetails($ex->school->id,$totalAve / $count )->remark) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="bg-light border-bottom border-dark">
                <div class="row px-4">
                    @foreach (Mk::getGradeList($ex->school->id) as $item )
                        <div class="col-6 {{$loop->odd?'border-end':''}} py-3 d-flex justify-content-between">
                            <span class="">{{ $item->mark_from }}-{{$item->mark_to}}</span>
                            <span>-></span>
                            <span>{{$item->name}}</span>
                            <span>{{$item->remark}}</span>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        @if (Mk::getMarkPreference($ex->marking_period_id)[0]->show_skills==1)
            <div class="skillPane border-start" style="width: 30%;">
                <table class="table m-0">
                    <thead>
                        <tr class="table-info">
                            <td><strong>PSYCHOMOTOR</strong></td>
                            <td><strong>RATING</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($skills->where('skill_type', 'PS') as $ps)
                        <tr class="text-start">
                            <td class="text-start">{{ $ps->name }}</td>
                            <td class="text-start">{{ $exr->ps ? explode(',', $exr->ps)[$loop->index] : '' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <table class="table m-0">
                    <thead>
                    <tr class="table-warning">
                        <td><strong>AFFECTIVE TRAITS</strong></td>
                        <td><strong>RATING</strong></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($skills->where('skill_type', 'AF') as $af)
                        <tr class="start">
                            <td class="text-start">{{ $af->name }}</td>
                            <td class="text-start">{{ $exr->af ? explode(',', $exr->af)[$loop->index] : '' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <table class="table m-0">
                    <thead>
                        <tr class="text-center table-light">
                            <th>KEY</th>
                            <th>REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>5</td>
                            <td>Excellent</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Very good</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Good</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Fair</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Poor</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>
