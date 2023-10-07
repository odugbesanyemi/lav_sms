@extends('layouts.master')
@section('page_title', 'Graduated Students')
@section('content')

<div class="">
    <div class="p-2">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-students" class="nav-link active" data-toggle="tab">All Graduated Students</a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Select Class</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($my_classes as $c)
                    <a href="#c{{ $c->id }}" class="dropdown-item" onclick="getData('{{ $c->id }}')" data-toggle="tab">{{ $c->title }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content md:p-4 border-l border-r border-b bg-white">
            <div class="tab-pane fade show active" id="all-students">
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
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM_No</th>
                            <th>Section</th>
                            <th>Grad Year</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="data-container-0">
                        </tbody>
                    </table>
                </div>

            </div>

            @foreach($my_classes as $mc)
            <div class="tab-pane fade" id="c{{$mc->id}}">
                <div class="search py-3">
                    <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                <i class="fi fi-rr-search text-xl text-slate-300 flex"></i>
                            </div>
                            <input oninput="searchData('{{ $mc->id }}')" type="text" id="dataSearch{{ $mc->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quick Search..." required>
                        </div>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>ADM_No</th>
                            <th>Section</th>
                            <th>Grad Year</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="data-container-{{ $mc->id }}">

                        </tbody>
                    </table>
                </div>

            </div>
            @endforeach

        </div>
    </div>
</div>

{{--Student List Ends--}}
<script>
    $(document).ready()
    {
        var records = {!! $students->toJson() !!};
        records = Object.values(records)
        function searchData(class_id){
            const searchInput = $('#dataSearch'+class_id)
            const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
            // Filter records based on the search
            const filteredRecords = records
            .filter((data)=>{
                return class_id==0?data:data.my_class_id == class_id
            })
            .filter(function (data) {
                return data.user.name.toLowerCase().includes(searchTerm) || (data.adm_no?data.adm_no.toLowerCase().includes(searchTerm):false) ; // Replace "someProperty" with the property you want to search
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
                    <tr >
                        <td>${index + 1}</td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="${data.user.photo}" alt="photo"></td>
                        <td>${data.user.name}</td>
                        <td>${data.adm_no}</td>
                        <td>${data.my_class.title} ${data.section.name}</td>
                        <td>${data.grad_date}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a href="{{ route('students.show', '') }}${data.id}" class="dropdown-item"><i class="icon-eye"></i> View Profile</a>
                                        @if(Qs::userIsTeamSA())
                                        <a href="{{ route('students.edit', '') }}${data.id}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                        <a href="{{ route('st.reset_pass', '') }}${data.user.id}" class="dropdown-item"><i class="icon-lock"></i> Reset password</a>

                                        {{--Not Graduated--}}
                                        <a id="${data.id}" href="#" onclick="$('form#ng-'+this.id).submit();" class="dropdown-item"><i class="icon-stairs-down"></i> Not Graduated</a>
                                            <form method="post" id="ng-${data.id}" action="{{ route('st.not_graduated', '') }}${data.id}" class="hidden">@csrf @method('put')</form>
                                        @endif

                                        <a target="_blank" href="{{ route('marks.year_selector', '') }}${data.user.id}" class="dropdown-item"><i class="icon-check"></i> Marksheet</a>

                                        {{--Delete--}}
                                        @if(Qs::userIsSuperAdmin())
                                        <a id="${data.user.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                        <form method="post" id="item-delete-${data.user.id}" action="{{ route('students.destroy', '') }}${data.user.id}" class="hidden">@csrf @method('delete')</form>
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
    }
    </script>
@endsection
