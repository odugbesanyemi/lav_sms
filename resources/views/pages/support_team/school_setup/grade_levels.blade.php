@extends('layouts.master')
@section('page_title', 'Grade Levels')
@section('content')
<style>
    .edit_input{
        border: 0;
        padding: 5px;
    }
    .edit_input:focus{
        border: 0;
        outline: 0;
        border-bottom: 1px solid gainsboro;

    }
    .edit_input_show{
        padding: 5px;
        border-bottom: 2px solid gainsboro;
    }
    .edit_input_show:focus{
        outline: 0;
    }
</style>
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
            <h6 class="card-title">Grade Levels</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form action="/setup/grade-levels/add" method="post" >
                @csrf @method('post')
                <div class="table overflow-x-auto" >
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Title</th>
                            <th>Short Name</th>
                            <th>Next Grade</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach (Qs::getSchoolGradeLevels() as $gl )
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>
                                        <input type="text" name="title" id="edit_title" class="edit_input" value="{{$gl->title}}" onchange="updateRecord(this,'{{$gl->id}}')">
                                    </td>
                                    <td><input type="text" name="short_name" id="edit_short_name" class="edit_input" value="{{$gl->short_name}}" onchange="updateRecord(this,'{{$gl->id}}')"></td>
                                    <td>
                                    <select name="next_grade_id" class="form-control border-0" id="next_grade_id" onchange="updateRecord(this,'{{$gl->id}}')">
                                        <option  value="0">-</option>
                                        @foreach (Qs::getAllGradeLevels() as $glo )
                                        <option  {{ $gl->next_grade_id == $glo->id?'selected':'' }} value="{{$glo->id}}">{{$glo->title}}</option>
                                        @endforeach
                                    </select>
                                    </td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                        @if(Qs::userIsSuperAdmin())
                                                    {{--Delete--}}
                                                    <a id="{{$gl->id}}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $gl->id }}" action="/setup/grade-levels/delete/{{ $gl->id }}" class="hidden">@csrf </form>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td>+</td>
                                    <td><input type="text" name="title" id="title" class="edit_input_show form-control" value="" ></td>
                                    <td><input type="text" name="short_name" id="short_name" class="edit_input_show form-control" value="" ></td>
                                    <td>
                                        <select name="next_grade_id" class="form-control" id="next_grade_id">
                                            <option value="0">Select Next Grade</option>
                                            @foreach (Qs::getSchoolGradeLevels() as $glo )
                                            <option value="{{$glo->id}}">{{$glo->title}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <input type="hidden" name="school_id" value="{{ Qs::findActiveSchool()[0]->id }}">
                                    <input type="hidden" name="acad_year_id" value="{{ Qs::getActiveAcademicYear()[0]->id }}">
                                </tr>
                        </tbody>
                    </table>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
            function updateRecord(element,id){
                var title = ($(element).attr('name'))
                var value = ($(element).val())
                var data = {
                    [title]:value
                }
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var url = `/setup/grade-levels/update/${id}`
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        data: data
                    },
                    success: () => {
                        flash({msg : 'grade updated Successfully', type : 'success'});
                    }
                });
            }
            function deleteRecord(id){
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var url = `/setup/grade-levels/delete/${id}`
                $.ajax({
                    url: url,
                    method: 'delete',
                    data: {
                        _token: csrfToken,
                    },
                    success: () => {
                        flash({msg : 'grade Deleted Successfully', type : 'success'});
                        location.reload();
                    }
                });
            }
    </script>
@endsection
