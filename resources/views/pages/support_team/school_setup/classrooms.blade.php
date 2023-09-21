@extends('layouts.master')
@section('page_title', 'rooms')
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
</style>
<div class="card shadow-none">
    <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
        <h6 class="card-title">Manage Classrooms</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-classrooms" class="nav-link active" data-toggle="tab">Manage rooms</a></li>
        </ul>

        <div class="tab-content p-4">
            <div class="tab-pane fade show active" id="all-classrooms">
                <form action="/setup/classrooms/add" method="post" >
                    @csrf @method('post')
                    <div class="" style="overflow-x: auto;" >
                        <table class="table">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Title</th>
                                <th>description</th>
                                <th>capacity</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                                @foreach (Qs::getAllClassrooms() as $c )
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <input type="text" name="title" id="edit_title" class="edit_input" value="{{$c->title}}" onchange="updateRecord(this,'{{$c->id}}')">
                                        </td>
                                        <td><input type="text" name="description" id="edit_description" class="edit_input" value="{{$c->description}}" onchange="updateRecord(this,'{{$c->id}}')"></td>
                                        <td><input type="number" name="capacity" id="edit_capacity" class="edit_input" value="{{$c->capacity}}" onchange="updateRecord(this,'{{$c->id}}')"></td>

                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-left">
                                                            @if(Qs::userIsSuperAdmin())
                                                        {{--Delete--}}
                                                        <a id="{{$c->id}}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                        <form method="post" id="item-delete-{{ $c->id }}" action="/setup/classrooms/delete/{{ $c->id }}" class="hidden">@csrf </form>
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
                                        <td><input type="text" name="description" id="description" class="edit_input_show form-control" value="" ></td>
                                        <td><input type="number" name="capacity" id="capacity" class="edit_input_show form-control" value="" ></td>
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
            var url = `/setup/classrooms/update/${id}`
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: csrfToken,
                    data: data
                },
                success: () => {
                    flash({msg : 'room updated Successfully', type : 'success'});
                }
            });
        }
        function deleteRecord(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var url = `/setup/classrooms/delete/${id}`
            $.ajax({
                url: url,
                method: 'delete',
                data: {
                    _token: csrfToken,
                },
                success: () => {
                    flash({msg : 'room Deleted Successfully', type : 'success'});
                    location.reload();
                }
            });
        }
</script>
@endsection
