@extends('layouts.master')
@section('page_title', 'Marking Periods')
@section('content')
<style>
    .mp_view ul{
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .mp_view li{
        border-left: 3px solid inherit;
        border-right: 3px solid inherit;
        border-radius: 5px;
        padding: 5px 8px 5px 20px;
    }
    .mp_view li a{
        text-decoration: none;
        width: 100%;
        display: flex;
    }

    .mp_view .frame-border{
        border: 1px dashed gainsboro;
        margin-bottom: 5px;
        padding: 10px;
        border-radius:5px;
    }



</style>
<div class="space-y-3">
    <div class="bg-white rounded-md shadow border-green-200 border-2 overflow-hidden" id="mp_view">
        <div class="px-3 border-b-2 bg-gradient-to-b from-green-400 to-green-500 text-green-50 border-b-green-200 header-elements-inline py-3">
            <h6 class="card-title" id="titleDisplay"> </h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="">
            <div class="card-body py-4 ">
                <form action="/setup/marking-periods/new" method="post">
                    @csrf
                    <div class="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" id="" placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label for="">Short Name</label>
                                <input type="text" class="form-control" name="short_name" id="" placeholder="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="commentsCheck" class="mt-3">
                                    <input type="hidden" name="does_comments" value="0">
                                    <input type="checkbox" class="" name="does_comments"  onchange="this.value=this.checked?1:0" id="commentsCheck">
                                    Allow Comments
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label for="gradedCheck" class="mt-3">
                                    <input type="hidden" name="does_grades" value="0">
                                    <input type="checkbox" class="" name="does_grades"  onchange="this.value=this.checked?1:0" id="gradedCheck">
                                    Graded
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label for="examCheck" class="mt-3">
                                    <input type="hidden" name="does_exams" value="0">
                                    <input type="checkbox" class="" name="does_exams"  onchange="this.value=this.checked?1:0" id="examCheck">
                                    Used for Exams
                                </label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="">Begins</label>
                                <input type="date" name="start_date" class="form-control"  id="">
                            </div>
                            <div class="col-md-6">
                                <label for="">Ends</label>
                                <input type="date" name="end_date" class="form-control" id="">
                            </div>
                        </div>
                        <div class="row mb-3" id="gradePosting">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="">Grade Posting Begins</label>
                                <input type="date" name="post_start_date" class="form-control" id="">
                            </div>
                            <div class="col-md-6">
                                <label for="">Grade Posting Ends</label>
                                <input type="date" name="post_end_date"  class="form-control" id="">
                            </div>
                        </div>
                        <input type="hidden" name="mp_type" id="mp_type" >
                        <input type="hidden" name="school_id" id="school_id" >
                        <input type="hidden" name="acad_year_id" id="acad_year_id" >
                        <input type="hidden" name="parent_id" id="parent_id" >
                    </div>
                    <div class="mt-3 mr-auto">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-b from-green-400 to-green-500 text-green-50 rounded flex items-center ">Submit<x-iconpark-send class="w-5 h-5"/></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-md shadow border-blue-200 border-2 overflow-hidden" id="mp_edit_view">
        <div class="px-3 border-b-2 bg-gradient-to-b from-blue-400 to-blue-500 text-blue-50 border-b-blue-200 header-elements-inline py-3">
            <h6 class="card-title" id="editTitleDisplay"> </h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="" >
            <div class="card-body py-4 ">
                <form action='' method="post">
                    @csrf
                    <div class="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label for="">Short Name</label>
                                <input type="text" class="form-control" name="short_name" id="short_name" placeholder="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="commentsCheck" class="mt-3">
                                    <input type="hidden" name="does_comments" value="0">
                                    <input type="checkbox" class="" name="does_comments"  onchange="this.value=this.checked?1:0" id="does_comments">
                                    Allow Comments
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label for="gradedCheck" class="mt-3">
                                    <input type="hidden" name="does_grades" value="0">
                                    <input type="checkbox" class="" name="does_grades"  onchange="this.value=this.checked?1:0" id="does_grades">
                                    Graded
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label for="examCheck" class="mt-3">
                                    <input type="hidden" name="does_exams" value="0">
                                    <input type="checkbox" class="" name="does_exams"  onchange="this.value=this.checked?1:0" id="does_exams">
                                    Used for Exams
                                </label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="">Begins</label>
                                <input type="date" name="start_date" class="form-control"  id="start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="">Ends</label>
                                <input type="date" name="end_date" class="form-control" id="end_date">
                            </div>
                        </div>
                        <div class="row mb-3" id="gradePosting">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="">Grade Posting Begins</label>
                                <input type="date" name="post_start_date" class="form-control" id="post_start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="">Grade Posting Ends</label>
                                <input type="date" name="post_end_date"  class="form-control" id="post_end_date">
                            </div>
                        </div>
                        <input type="hidden" name="mp_type" id="mp_type" >
                        <input type="hidden" name="school_id" id="school_id" >
                        <input type="hidden" name="acad_year_id" id="acad_year_id" >
                        <input type="hidden" name="parent_id" id="parent_id" >
                        <input type="hidden" name="id" id="id" >
                    </div>
                    <div class="mt-3 mr-auto">
                        <button type="button" onclick="submitEdit()" class="px-4 py-2 bg-gradient-to-b from-blue-400 to-blue-500 text-blue-50 rounded flex items-center gap-2">Continue <x-iconpark-send class="w-5 h-5"/></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mp_view gap-3 grid md:grid-cols-2" >
        <div class="">
            <div class="bg-white rounded-md shadow border-red-200 border-2 overflow-hidden">
                <div class="px-3 text-secondary border-b-2 bg-gradient-to-b from-red-400 to-red-500 text-red-50 border-b-red-200 header-elements-inline py-3">
                    <h6 class="card-title text-white">SEMESTER </h6>
                    {!! Qs::getPanelOptions() !!}
                </div>
                <div class="p-3">
                    @if (Qs::getSemesters()->count()>=1)
                    <!-- meaning there are exisiting semesters -->

                        <ul class="grid grid-cols-1 gap-2   ">
                            @foreach (Qs::getSemesters() as $s )
                                <li  class="d-flex align-items-center justify-content-between bg-red-50 shadow-inner text-red-600"><a onclick="editSemester('{{$s->id}}')" href="#{{ $s->title }}">{{ $s->title }}</a>
                                <button class="px-3 py-1 rounded bg-gradient-to-b from-red-500 to-red-600 text-white" onclick="editMarkingPeriod('{{$s->id}}')">edit</button>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="py-1 frame-border" >
                            <h4 class="m-0 p-2 font-medium text-red-400">No Semesters Found</h4>
                        </div>
                    @endif


                    <button class="px-4 py-2 rounded btn-secondary mt-3 bg-gradient-to-b from-red-400  to-red-500 text-red-50 flex items-center gap-2" id="createSemBtn">Create New <x-iconpark-send class="w-5 h-5"/></button>
                </div>
            </div>
        </div>

        <div class="" id="mp_quarter_view">
            <div class="bg-white rounded-md shadow border-indigo-200 border-2 overflow-hidden">
                <div class="px-3 text-secondary border-b-2 bg-gradient-to-b from-indigo-400 to-indigo-500 text-indigo-50 border-b-indigo-200 header-elements-inline py-3">
                    <h6 class="card-title text-indigo-50" id="quarterTitle"> </h6>
                    {!! Qs::getPanelOptions() !!}
                </div>

                <div class="p-3" >
                    <div id="loading" class="py-2">loading...</div>
                    <div class="" id="quarterView">
                        <!-- <h2>No Quarters Found</h2> -->
                        <!-- <button class=""></button> -->
                    </div>
                    <button class="px-4 py-2 bg-gradient-to-b mt-3 from-indigo-400 to-indigo-500 text-indigo-50 rounded flex items-center gap-2" id="createSemQtrBtn">Create New <x-iconpark-send class="w-5 h-5"/></button>
                </div>
            </div>
        </div>

    </div>

</div>

    <script>

        function editMarkingPeriod(id){
            $.ajax({
                url:`/setup/ajax/marking-period/${id}`,
                method:"",
                success:(data)=>{
                    $('#mp_edit_view').show('slow');
                    $('#editTitleDisplay').text(data[0].title)
                    $('#title').val(data[0].title)
                    $('#short_name').val(data[0].short_name)
                    $('#acad_year_id').val(data[0].acad_year_id)
                    $('#does_comments').prop('checked',data[0].does_comments==1?true:false).val(data[0].does_comments)
                    $('#does_exams').prop('checked',data[0].does_exams==1?true:false).val(data[0].does_exams)
                    $('#does_grades').prop('checked',data[0].does_grades==1?true:false).val(data[0].does_grades)
                    $('#end_date').val(data[0].end_date)
                    $('#mp_type').val(data[0].mp_type)
                    $('#parent_id').val(data[0].parent_id)
                    $('#post_end_date').val(data[0].post_end_date)
                    $('#post_start_date').val(data[0].post_start_date)
                    $('#school_id').val(data[0].school_id)
                    $('#start_date').val(data[0].start_date)
                    $('#id').val(data[0].id)
                },
            })

        }
        function editSemester(id){
            var noQuarterFound;
            var dataContainer;
            var dataElem;
            dataContainer = $('<ul></ul>');
            dataElem = $('<li class="rounded-full border-red-400 border-2 border"></li>')
            noQuarterFound = $('<h4 class="m-0 text-xl font-bold p-2"></h4>').text('No Quarter Found')
            noQuarterFound.attr('class','py-1 frame-border')

            $('#mp_quarter_view').show('slow');
            $('#createSemQtrBtn').click(function(){
                $('#mp_view').show('slow')
                $('#mp_type').val('quarter')
                $('#school_id').val('{{ Qs::findActiveSchool()[0]->id }}')
                $('#acad_year_id').val('{{ Qs::getActiveAcademicYear()[0]->id }}')
                $('#parent_id').val(id)
                $('#titleDisplay').text('New Quarter')
            })
            $('#loading').show()
            $.ajax({
                url:`/setup/ajax/getSemesterQuarters/${id}`,
                method:"get",
                success:function(data){
                    $('#quarterTitle').text(data['semester'][0].title)
                    if(data['semester_quarter'].length < 1){
                        // meaning there is no data
                        $('#quarterView').html(noQuarterFound)
                    }else{
                        data['semester_quarter'].forEach(element => {
                            // var dataElem = $('<li></li>').text(element.title)
                            var listItem = $('<li class="d-flex align-items-center justify-content-between bg-indigo-50 shadow-inner"></li>');
                            listItem.addClass('d-flex align-items-center justify-content-between rounded-2xl')

                            // create anchor
                            var anchor = $('<a></a>');
                            anchor.text(element.title);
                            anchor.prop('href', `#${element.title}`);

                            // create button
                            var button = $('<button></button>');
                            button.addClass('px-3 py-1 rounded bg-gradient-to-b from-indigo-500 to-indigo-600 text-white');
                            button.text('edit')
                            button.click(()=>{
                                editMarkingPeriod(element.id)
                            })

                            // append the anchor and button to the list item
                            listItem.append(anchor)
                            listItem.append(button)
                            dataContainer.append(listItem)
                        });
                        $('#quarterView').html(dataContainer)

                    }
                    $('#loading').hide()

                }
            })
        }
        function submitEdit(){
            var data = {
                title: $('#title').val(),
                short_name: $('#short_name').val(),
                does_comments: $('#does_comments').val(),
                does_exams: $('#does_exams').val(),
                does_grades: $('#does_grades').val(),
                end_date: $('#end_date').val(),
                mp_type: $('#mp_type').val(),
                parent_id: $('#parent_id').val(),
                post_end_date: $('#post_end_date').val(),
                post_start_date: $('#post_start_date').val(),
                school_id: $('#school_id').val(),
                acad_year_id:$('#acad_year_id').val(),
                start_date: $('#start_date').val(),
                id:$('#id').val(),
            };

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/setup/edit_marking_period',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    data: data
                },
                success: () => {
                    flash({msg : 'Edit Success', type : 'success'});
                }
            });

        }
        $(document).ready()
        {
            $('#loading').hide();
            $('#mp_view').hide();
            $('#mp_edit_view').hide();
            $('#mp_quarter_view').hide();
            $('#gradePosting').hide();
            $('#examCheck').prop('disabled',true)
            $('#gradedCheck').change(function(){
                if($(this).prop('checked')){
                    $('#examCheck').prop('disabled',false)
                    $('#gradePosting').show();
                }else{
                    $('#examCheck').prop('disabled', true);
                    $('#gradePosting').hide();
                }
            })
            $('#createSemBtn').click(function(){
                $('#mp_view').show('slow')
                $('#mp_type').val('semester')
                $('#school_id').val('{{ Qs::findActiveSchool()[0]->id }}')
                $('#acad_year_id').val('{{ Qs::getActiveAcademicYear()[0]->id }}')
                $('#parent_id').val('-{{ Qs::getActiveAcademicYear()[0]->id }}')
                $('#titleDisplay').text('New Semester')
            })


        }
    </script>
@endsection
