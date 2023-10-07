@extends('layouts.master')
@section('page_title', 'Manage Skills Type')
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
            <h6 class="card-title font-weight-bold">Add Skills Type</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">

            <form action="/marks/setup/add-skill-type" method="post" >
                @csrf @method('post')
                <div class="" style="overflow-x: auto;" >
                    <table class="table">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Short Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach ($type as $st )
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>
                                        <input type="text" name="name" id="edit_name" class="edit_input" value="{{$st->name}}" onchange="updateRecord(this,'{{$st->id}}')">
                                    </td>
                                    <td><input type="text" name="short_name" id="edit_short_name" class="edit_input" value="{{ $st->short_name }}" onchange="updateRecord(this,'{{ $st->id }}')"></td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                        @if(Qs::userIsSuperAdmin())
                                                    {{--Delete--}}
                                                    <a id="{{$st->id}}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $st->id }}" action="/marks/setup/skill-type/delete/{{ $st->id }}" class="hidden">@csrf </form>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td>+</td>
                                    <td><input type="text" name="name" id="name" class="edit_input_show form-control" value="" placeholder="Pschomotor" required></td>
                                    <td><input type="text" name="short_name" id="short_name" class="edit_input_show form-control" placeholder="PS" required></td>
                                    <td><button type="submit" class="btn btn-primary">Save</button></td>
                                </tr>
                        </tbody>
                    </table>

                </div>

            </form>
        </div>
    </div>
    <script>
        function updateRecord(element,id){
            $('#ajax-loader').show();
            var title = ($(element).attr('name'))
            var value = ($(element).val())
            var data = {
                [title]:value
            }
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var url = `/marks/setup/skill-type/update/${id}`
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: csrfToken,
                    data: data
                },
                success: () => {
                    flash({msg : 'skill Type updated Successfully', type : 'success'});
                    $('#ajax-loader').hide();
                }
            });
        }
        function deleteRecord(id){
            $('#ajax-loader').show();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var url = `/marks/setup/skill-type/delete/${id}`
            $.ajax({
                url: url,
                method: 'delete',
                data: {
                    _token: csrfToken,
                },
                success: () => {
                    flash({msg : 'Skill Type Deleted Successfully', type : 'success'});
                    location.reload();
                }
            });
        }
    </script>

@endsection
