@extends('layouts.master')
@section('page_title', 'Manage Remarks')
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
    .expanded {
        width: 300px !important; /* Set your desired width here */
    }
</style>
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h6 class="card-title font-weight-bold">Exam Remarks</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">

            <form action="/marks/setup/add-remark" method="post" >
                @csrf @method('post')
                <div class="overflow-x-auto"  >
                    @if (count($remarks)==0)
                        <div class="md:p-4 p-2">
                            <h1 class="text-red-500">No Remarks Yet!</h1>
                            <span>Manage remarks for student marks. begin now.</span>
                        </div>
                    @endif
                    <table class="table table-fixed">
                        <thead>
                        <tr>
                            <th class="w-16">S/N</th>
                            <th class="w-60">Remark</th>
                            <th class="w-36">User Type</th>
                            <th class="w-36">Grades</th>
                            <th class="w-20">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach ($remarks as $r )
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="">
                                        <textarea type="text" style="width:100%;" name="remark" id="edit_remark" class="edit_input"  onchange="updateRecord(this,'{{$r->id}}')">{{$r->remark}}</textarea>
                                    </td>
                                    <td>
                                        <select name="userType" id="edit_userType" class="form-control" onchange="updateRecord(this,'{{ $r->id }}')">
                                            <option value="0">Select User Types</option>
                                            @foreach ($userType as $ut )
                                                <option {{ $r->userType == $ut?'selected':'' }} value="{{ $ut }}">{{ $ut }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="grade_id" id="edit_grade-id" class="form-control" onchange="updateRecord(this,'{{ $r->id }}')">
                                            <option value="0">Select Grades</option>
                                            @foreach ($grades as $g )
                                                <option {{ $r->grade_id == $g->id?'selected':'' }} value="{{ $g->id }}">{{ $g->name }} - {{ $g->remark }}</option>
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
                                                    <a id="{{$r->id}}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $r->id }}" action="/marks/setup/remark/delete/{{ $r->id }}" class="hidden">@csrf </form>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="w-full overflow-x-auto grid gap-2 md:grid-flow-col-dense">
                    <span>+</span>
                    <span class="max-sm:w-full flex flex-col gap-2 border rounded p-1">
                        <span class="flex gap-1 " id="placeholder-group">
                            <button type="button" onclick="addPlaceholder(this)" data-placeholder="[student]" class="bg-orange-100 hover:bg-orange-200 text-orange-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-orange-400 border border-orange-400 inline-flex items-center justify-center">student Name</button>
                        </span>
                        <textarea type="text" style="width:100%;" onfocus="expandWidth(this)" onblur="resetWidth(this)" name="remark" id="remark" class="max-sm:w-full border-0 focus:border-0 focus:outline-0 "  placeholder="[student] is a good learner"></textarea>
                    </span>
                    <span>
                        <select name="userType" style="width:100%;" id="userType" class="form-control" onfocus="expandWidth(this)" onblur="resetWidth(this)">
                            <option value="0">Select User Types</option>
                            @foreach ($userType as $ut )
                                <option value="{{ $ut }}">{{ $ut }}</option>
                            @endforeach
                        </select>
                    </span>
                    <span>
                        <select name="grade_id" style="width:100%;" id="edit_grade-id" onfocus="expandWidth(this)" onblur="resetWidth(this)" class="form-control">
                            <option value="0">Select Grades</option>
                            @foreach ($grades as $g )
                                <option  value="{{ $g->id }}">{{ $g->name }} - {{ $g->remark }}</option>
                            @endforeach
                        </select>
                    </span>
                    <input type="hidden" name="school_id" value="{{ Qs::findActiveSchool()[0]->id }}">
                    <span class="w-full"><button type="submit" class="btn btn-primary flex w-full"><i class="fi fi-rr-bookmark mr-2"></i>Save</button></span>
                </div>
            </form>
        </div>
    </div>


    <script>
    $(document).ready()
    {
        function updateRecord(element,id){
            $('#ajax-loader').show();
            var title = ($(element).attr('name'))
            var value = ($(element).val())
            var data = {
                [title]:value
            }
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var url = `/marks/setup/remark/update/${id}`
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: csrfToken,
                    data: data
                },
                success: () => {
                    flash({msg : 'Remark updated Successfully', type : 'success'});
                    $('#ajax-loader').hide();
                }
            });
        }
        function deleteRecord(id){
            $('#ajax-loader').show();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var url = `/marks/setup/remark/delete/${id}`
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
        function expandWidth(el){
            $(this).addClass('expanded')
        }
        function resetWidth(el){
            $(this).removeClass('expanded')
        }
        function insertPlaceholder(text) {
            var inputEl = $('#remark');
            var currPos = inputEl.prop('selectionStart');
            var curValue = inputEl.val(); // Use val() instead of text()

            // Check if the textarea is empty
            if (curValue === undefined || curValue === null) {
                inputEl.val(text); // Set the value with the placeholder text
            } else {
                var updatedText = curValue.slice(0, currPos) + text + curValue.slice(currPos);
                inputEl.val(updatedText); // Set the updated value
                inputEl.prop('selectionStart', currPos + text.length);
                inputEl.prop('selectionEnd', currPos + text.length);
            }
        }
        function addPlaceholder(el){
            var text= $(el).data('placeholder');
            insertPlaceholder(text);
        }

    }

    </script>

@endsection
