<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id]) }}" method="post">
    @csrf @method('put')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>ADM_NO</th>
            <th>CA ({{ $mp->ca_final_score?$mp->ca_final_score:40 }})</th>
            <th>EXAM ({{ $mp->exam_final_score?$mp->exam_final_score:60 }})</th>
        </tr>
        </thead>
        <tbody>
        @foreach($marks->sortBy('user.name') as $mk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mk->user->name }} </td>
                <td>{{ $mk->user->student_record->adm_no }}</td>

                {{-- CA AND EXAMS --}}
                <td><input {{Mk::isMarkingPeriod($mp->marking_period_id)?'':'disabled'}} title="1ST CA" min="1" max="{{ $mp->ca_final_score?$mp->ca_final_score:40 }}" class="text-center form-input" name="ca_score_{{$mk->id}}" value="{{ $mk->ca_score }}" type="number"></td>
                <td><input {{Mk::isMarkingPeriod($mp->marking_period_id)?'':'disabled'}} title="EXAM" min="1" max="{{ $mp->exam_final_score?$mp->exam_final_score:60 }}" class="text-center form-input" name="exam_score_{{$mk->id}}" value="{{ $mk->exam_score }}" type="number"></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class=" mt-2">
        <button type="submit" class="btn btn-primary">Update Marks <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>
