

<table align="center" class="table table-bordered align-middle text-center m-0 table-striped">
    <thead>
        <tr class=""><th colspan="9" class="fs-3 fw-bold p-3 table-light">
            COGNITIVE DOMAIN
        </th></tr>
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