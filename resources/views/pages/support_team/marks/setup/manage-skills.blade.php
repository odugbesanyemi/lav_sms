@extends('layouts.master')
@section('page_title', 'Manage Skills')
@section('content')
<style>
    .edit_input{
        border: 0;
        padding: 5px;
        width: 100% !important;
        background-color: white !important;
    }
    .edit_input:focus{
        border: 0;
        outline: 0;
        border-bottom: 1px solid gainsboro;

    }
    .edit_input_show{
        padding: 5px;
    }
    .edit_input_show:focus{
        outline: 0;
    }
</style>
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h6 class="card-title font-weight-bold">Manage School Exam Skills</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">

            <form action="/marks/setup/add-skill" method="post" >
                @csrf @method('post')
                <div class="" style="overflow-x: auto;" >
                    <table class="table">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Skill Type</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach ($skills as $sk )
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>
                                        <input type="text" name="name" id="edit_name" class="edit_input" value="{{$sk->name}}" onchange="updateRecord(this,'{{$sk->id}}')">
                                    </td>
                                    <td>
                                        <select name="skill_type" class="edit_input" id="" onchange="updateRecord(this,'{{$sk->id}}')">
                                            @foreach ($skills->pluck('skill_type')->unique() as $skillType )
                                                <option {{ $sk->skill_type == $skillType?'selected':'' }} value="{{ $skillType }}">{{$skillType=='AF'?"AFFECTIVE":"PSYCHOMOTOR"}}</option>
                                            @endforeach
                                        </select>
                                        <!-- <input type="text" name="skill_type" id="edit_skill_type" class="edit_input" value="{{$sk->skill_type=='AF'?'AFFECTIVE':'PSYCHOMOTOR'}}" onchange="updateRecord(this,'{{$sk->id}}')"> -->
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
                                                    <a id="{{$sk->id}}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $sk->id }}" action="/marks/setup/delete/{{ $sk->id }}" class="hidden">@csrf </form>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td>+</td>
                                    <td><input type="text" name="name" id="name" class="edit_input_show form-control" value="" placeholder="Skill Name"></td>
                                    <td>
                                        <select name="skill_type" class="form-control" id="skill_type">
                                            <option value="0">Select Skill Type</option>
                                            @foreach ($skills->pluck('skill_type')->unique() as $skillType )
                                                <option value="{{ $skillType}}">{{$skillType=='AF'?"AFFECTIVE":"PSYCHOMOTOR"}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><button type="submit" class="btn btn-primary">Save</button></td>
                                    <input type="hidden" name="school_id" value="{{ Qs::findActiveSchool()[0]->id }}">
                                </tr>
                        </tbody>
                    </table>

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
                var url = `/marks/setup/update/${id}`
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        data: data
                    },
                    success: () => {
                        flash({msg : 'skill updated Successfully', type : 'success'});
                    }
                });
            }
            function deleteRecord(id){
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var url = `/marks/setup/delete/${id}`
                $.ajax({
                    url: url,
                    method: 'delete',
                    data: {
                        _token: csrfToken,
                    },
                    success: () => {
                        flash({msg : 'Skill Deleted Successfully', type : 'success'});
                        location.reload();
                    }
                });
            }
    </script>

@endsection
