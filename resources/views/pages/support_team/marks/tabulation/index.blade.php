@extends('layouts.master')
@section('page_title', 'Tabulation Sheet')
@section('content')
<div class="space-y-3">
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h5 class="card-title text-purple-900"><i class="icon-books mr-2 text-purple-800/80"></i> Tabulation Sheet</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('marks.tabulation_select') }}" class="space-y-3">
                @csrf
                <div class="grid md:grid-flow-col-dense flex-wrap items-end gap-3">

                    <div class="">
                        <div class="">
                            <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                            <select required id="exam_id" name="exam_id" class="form-control select" data-placeholder="Select Exam">
                                @foreach($exams as $exm)
                                    <option {{ ($selected && $exam_id == $exm->id) ? 'selected' : '' }} value="{{ $exm->id }}">{{ $exm->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="">
                        <div class="">
                            <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                            <select onchange="getClassSections(this.value)" required id="my_class_id" name="my_class_id" class="form-control select" data-placeholder="Select Class">
                                <option value=""></option>
                                @foreach($my_classes as $c)
                                    <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="">
                        <div class="">
                            <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                            <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                @if($selected)
                                    @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                        <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
                <div class="mt-3">
                    <div class="">
                        <button type="submit" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2  focus:outline-none flex items-center">View Sheet <i class="icon-paperplane ml-2 flex"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--if Selction Has Been Made --}}

    @if($selected)
        <div class="card">
            <div class="card-header">
                <h6 class="card-title font-weight-bold text-purple-900">Tabulation Sheet for {{ $my_class->title.' '.$section->name.' - '.$ex->name.' ('.$ex->acad_year->title.')' }}</h6>
            </div>
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table w-full table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>NAMES_OF_STUDENTS_IN_CLASS</th>
                        @foreach($subjects as $sub)
                        <th title="{{ $sub->name }}" rowspan="2">{{ strtoupper($sub->slug ?: $sub->name) }}</th>
                        @endforeach

                            <th style="color: darkred">Total</th>
                            <th style="color: darkblue">Average</th>
                            <th style="color: darkgreen">Position</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $s)
                                @php
                                    $cumTotal = 0;
                                    $totalSubjects = 0;
                                    $allStudents = [];
                                @endphp
                            
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="text-align: center">{{ $s->user->name }}</td>
                                    @foreach($subjects as $sub)
                                        @php
                                            $ca_score = $marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->ca_score;
                                            $exam_score = $marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->exam_score;
                                            $total = $ca_score + $exam_score;
                                            $cumTotal += $total;
                                            $total == 0 ? $totalSubjects + 0 : $totalSubjects++;
                                        @endphp
                                        <td>{{ $total }}</td>
                                    @endforeach
                                    <td style="color: darkred">{{ $cumTotal ?: '-' }}</td>
                                    <td style="color: darkblue">{{ ($cumTotal / $totalSubjects) ?: '-' }}</td>
                                    <td style="color: darkgreen">
                                        @php
                                            // Sort students by cumulative total scores in descending order
                                            $sortedStudents = $students->sortByDesc(function ($student) use ($marks, $subjects) {
                                                $cumTotal = 0;
                                                $totalSubjects = 0;
                                                foreach ($subjects as $sub) {
                                                    $ca_score = $marks->where('student_id', $student->user_id)->where('subject_id', $sub->id)->first()->ca_score;
                                                    $exam_score = $marks->where('student_id', $student->user_id)->where('subject_id', $sub->id)->first()->exam_score;
                                                    $total = $ca_score + $exam_score;
                                                    $cumTotal += $total;
                                                    $total == 0 ? $totalSubjects + 0 : $totalSubjects++;
                                                }
                                                return $cumTotal;
                                            });
                            
                                            // Get the position of the current student
                                            $position = $sortedStudents->search(function ($item, $key) use ($s) {
                                                return $item->id == $s->id;
                                            });
                            
                                            // Add 1 to the position to start from 1 instead of 0
                                            $position += 1;
                                            echo $position;
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                    </table>                    
                </div>

                {{--Print Button--}}
                <div class="text-center mt-4">
                    <a target="_blank" href="{{  route('marks.print_tabulation', [$exam_id, $my_class_id, $section_id]) }}" class="btn btn-danger btn-lg"><i class="icon-printer mr-2"></i> Print Tabulation Sheet</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
