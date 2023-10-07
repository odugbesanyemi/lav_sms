<html>
<head>
    <title>Tabulation Sheet - {{ $my_class->name.' '.$section->name.' - '.$ex->name.' ('.$year.')' }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/print_tabulation.css') }}" />
</head>
<body>
<div class="container">
    <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
        {{--    Logo N School Details--}}
        <table width="100%">
            <tr>
                {{--<td><img src="{{ $s['logo'] }}" style="max-height : 100px;"></td>--}}

                <td >
                    <strong><span style="color: #1b0c80; font-size: 25px;">{{ strtoupper(Qs::getSetting('system_name')) }}</span></strong><br/>
                    {{-- <strong><span style="color: #1b0c80; font-size: 20px;">MINNA, NIGER STATE</span></strong><br/>--}}
                    <strong><span
                                style="color: #000; font-size: 15px;"><i>{{ ucwords($s['address']) }}</i></span></strong><br/>
                    <strong><span style="color: #000; font-size: 15px;"> TABULATION SHEET FOR {{ strtoupper($my_class->title.' '.$section->name.' - '.$ex->name.' ('.$ex->acad_year->title.')' ) }}
                    </span></strong>
                </td>
            </tr>
        </table>
        <br/>

        {{--Background Logo--}}
        <div style="position: relative;  text-align: center; ">
            <img src="{{ $s['logo'] }}"
                 style="max-width: 500px; max-height:600px; margin-top: 60px; position:absolute ; opacity: 0.2; margin-left: auto;margin-right: auto; left: 0; right: 0;" />
        </div>

        {{-- Tabulation Begins --}}
        <table style="width:100%; border-collapse:collapse; border: 1px solid #000; margin: 10px auto;" border="1">
            <thead>
            <tr>
                <th>#</th>
                <th>NAMES_OF_STUDENTS_IN_CLASS</th>
                @foreach($subjects as $sub)
                    <th rowspan="2">{{ strtoupper($sub->slug ?: $sub->name) }}</th>
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
                    <td style="color: darkblue">{{ ($cumTotal/$totalSubjects) ?: '-' }}</td>
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
</div>

<script>
    window.print();
</script>
</body>
</html>
