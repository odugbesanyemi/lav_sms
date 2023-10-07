<html>
<head>
    <title>Student Marksheet - {{ $sr->user->name }}</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="bg-secondary-100 row">
    <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
        {{--    Logo N School Details--}}
        <div class=" border-dark border-bottom" id="StudentInfo">
            <div class="d-flex border-bottom align-items-center justify-content-between">
                <div class="col-2">
                    <img src="{{ $s['logo'] }}" style="max-height : 100px;">
                </div>
                <div class="col py-3 text-center">
                    <h3 class="fw-bold" style="color: #1b0c80; font-size: 35px;font-weight:semi-bold;">{{ strtoupper($sr->school->generic_name) }}</h3>
                    <p style="color: #000; font-size: 12px;"><i>{{ ucwords($s['address']) }}</i></p>
                </div>
                <div class="col-2 p-1 ml-auto text-center">
                    <img src="{{ $sr->user->photo }}" alt="..."  width="100" height="100">
                </div>
            </div>
            <div style="background-color:#1b0c80;">
                <p class="p-2 mb-0 text-center text-white fs-3 fw-semibold">{{Mk::reportTypeName($ex->marking_period_id)}} REPORT</p>
            </div>

            <table class="table table-bordered m-0">
                <thead class="align-middle text-center">
                    <tr>
                        <th colspan="2" rowspan="2">STUDENT NAME</th>
                        <th colspan="4"> {{ strtoupper($sr->user->name) }}</th>
                        <th rowspan="2">ADM. NO</th>
                        <td rowspan="2">{{ $sr->adm_no }}</td>
                    </tr>
                    <tr class="fst-italic" style="font-size: 12px;">
                        <td colspan="2">surname</td>
                        <td colspan="2">Other names</td>
                    </tr>
                    <tr>
                        <th>CLASS</th>
                        <td>{{ strtoupper($my_class->title) }}</td>
                        <th>SEX</th>
                        <td>{{strtoupper($sr->gender)?strtoupper($sr->gender):'NULL'}}</td>
                        <th>HOUSE</th>
                        <td>{{strtoupper($sr->house)?strtoupper($sr->house):'NULL'}}</td>
                        <th>SESSION</th>
                        <td>{{Qs::findAcademicYearById($year)[0]->title}}</td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>


        {{--Background Logo--}}
        <div style="position: relative;  text-align: center; ">
            <img src="{{ $s['logo'] }}"
                 style="max-width: 500px; max-height:600px; margin-top: 60px; position:absolute ; opacity: 0.2; margin-left: auto;margin-right: auto; left: 0; right: 0;" />
        </div>

        {{--<!-- SHEET BEGINS HERE-->--}}
        @include('pages.support_team.marks.print.sheet')

        {{--    COMMENTS & SIGNATURE    --}}
        @include('pages.support_team.marks.print.comments')

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
<script>
    // window.print();
</script>
</body>

</html>
