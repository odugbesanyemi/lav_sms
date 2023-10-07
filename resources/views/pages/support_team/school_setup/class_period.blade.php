@extends('layouts.master')
@section('page_title', 'class periods')
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
            <h6 class="card-title">Manage Class Periods</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#classes" class="nav-link active" data-toggle="tab">Manage Class Periods</a></li>
                <li class="nav-item"><a href="#new-class-period" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Period</a></li>
            </ul>

            <div class="tab-content md:p-4">
                <div class="tab-pane fade show active" id="classes">
                    <div class="search py-3">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                    <i class="fi fi-rr-search text-xl text-slate-300 flex"></i>
                                </div>
                                <input oninput="searchData(0)" type="text" id="dataSearch0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quick Search..." required>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto" >
                        <table class="w-full table">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Title</th>
                                <th>Short Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Length (minutes)</th>
                                <th>Used for Attendance</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-0">
                                <!-- meaning there is no data -->
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="tab-pane fade" id="new-class-period">
                    <div class="">
                        <form action="/setup/class-periods/new" method="post">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ Qs::findActiveSchool()[0]->id }}">
                            <input type="hidden" name="acad_year_id" value="{{ Qs::getActiveAcademicYear()[0]->id  }}">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" required id="title" class="form-control w-100" placeholder="Assembly">
                                </div>
                                <div class="col-md-6">
                                    <label for="short_name">Short Name</label>
                                    <input type="text" name="short_name" required id="short_name" class="form-control w-100" placeholder="A">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="start_time">Start Time</label>
                                    <input type="time" required name="start_time" id="start_time" class="form-control w-100">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_time">End Time</label>
                                    <input type="time" required name="end_time" id="end_time" class="form-control w-100">
                                </div>
                                <div class="col-md-4">
                                    <label for="length">Length (minutes)</label>
                                    <input type="number" name="length" id="length" class="form-control w-100">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="hidden" name="used_for_attendance" value="0">
                                    <input type="checkbox" name="used_for_attendance" id="used_for_attendance" onchange="this.value=this.checked?1:0">
                                    <label for="used_for_attendance">Used for Attendance</label>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
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
                var url = `/setup/class-periods/update/${id}`
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        data: data
                    },
                    success: () => {
                        flash({msg : 'Period updated Successfully', type : 'success'});
                    }
                });
            }
            function deleteRecord(id){
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var url = `/setup/class-periods/delete/${id}`
                $.ajax({
                    url: url,
                    method: 'delete',
                    data: {
                        _token: csrfToken,
                    },
                    success: () => {
                        flash({msg : 'Period Deleted Successfully', type : 'success'});
                        location.reload();
                    }
                });
            }
        $(document).ready()
        {
            // for updating records
            var records = {!! Qs::getAllClassPeriods()->toJson() !!};
            function searchData(class_id){
                const searchInput = $('#dataSearch'+class_id)
                const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
                // Filter records based on the search
                const filteredRecords = records
                .filter((data)=>{
                    return class_id==0?data:data.my_class_id == class_id
                })
                .filter(function (data) {
                    return data.title.toLowerCase().includes(searchTerm) || data.short_name.toLowerCase().includes(searchTerm) ; // Replace "someProperty" with the property you want to search
                });

                // Update the displayed records
                display(filteredRecords,class_id);
            }
            function display(records,id){

                $('#data-container-'+id).empty(); // Clear previous results
                // filter records based on id
                records
                .filter((item)=>{
                    return id==0?item:item.my_class_id == id
                })
                .forEach(function(data,index){
                    $('#data-container-'+id).append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <input type="text" name="title" id="edit_title" class="edit_input" value="${data.title}" onchange="updateRecord(this,'${data.id}')">
                        </td>
                        <td><input type="text" name="short_name" id="edit_short_name" class="edit_input" value="${data.short_name}" onchange="updateRecord(this,'${data.id}')"></td>
                        <td><input type="time" name="start_time" id="edit_start_time" class="edit_input" value="${data.start_time}" onchange="updateRecord(this,'${data.id}')"></td>
                        <td><input type="time" name="end_time" id="edit_end_time" class="edit_input" value="${data.end_time}" onchange="updateRecord(this,'${data.id}')"></td>
                        <td><input type="number" name="length" id="edit_length" class="edit_input" value="${data.length}" onchange="updateRecord(this,'${data.id}')"></td>
                        <td><input type="checkbox" name="used_for_attendance" id="edit_edit_title" class="edit_input"  ${ data.used_for_attendance==1?'checked':"" } onchange="this.value=this.checked?1:0;updateRecord(this,'${data.id}')"></td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                            @if(Qs::userIsSuperAdmin())
                                        {{--Delete--}}
                                        <a id="${data.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                        <form method="post" id="item-delete-${data.id}" action="/setup/class-periods/delete/${data.id}" class="hidden">@csrf @method('delete')</form>
                                            @endif

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    `);
                });
            };
            function getData(id=null){
                if(id==null){
                    id=0;
                }
                display(records,id)
            }
            getData();
            var startTimeField = $('#start_time')
            var endTimeField = $('#end_time')
            var lengthField = $('#length')
            endTimeField.change(()=>{
                // Convert time strings to minutes
                var time1Parts = startTimeField.val().split(':');
                var time2Parts = endTimeField.val().split(':');

                var hours1 = parseInt(time1Parts[0], 10);
                var minutes1 = parseInt(time1Parts[1], 10);
                var hours2 = parseInt(time2Parts[0], 10);
                var minutes2 = parseInt(time2Parts[1], 10);

                // Calculate the time difference in minutes
                var timeDifferenceMinutes = (hours2 * 60 + minutes2) - (hours1 * 60 + minutes1);

                // Optional: Convert the difference back to hours and minutes if needed
                lengthField.val(timeDifferenceMinutes)
            })
        }
    </script>
@endsection
