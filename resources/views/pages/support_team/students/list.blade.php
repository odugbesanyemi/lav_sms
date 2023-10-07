@extends('layouts.master')
@section('page_title', 'Student Information - '.$my_class->title)
@section('content')

    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
            <h6 class="card-title">Students List</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-students" class="nav-link active" data-toggle="tab">All {{ $my_class->title }} Students</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Sections</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach($sections as $s)
                            <a href="#s{{ $s->id }}" class="dropdown-item" onclick="getData('{{ $s->id }}')" data-toggle="tab">{{ $my_class->title.' '.$s->name }}</a>
                        @endforeach
                    </div>
                </li>
            </ul>

            <div class="tab-content p-md-4">
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
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-0">

                            </tbody>
                        </table>
                    </div>

                </div>

                @foreach($sections as $se)

                    <div class="tab-pane fade" id="s{{$se->id}}">
                        <div class="search py-3">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                        <i class="fi fi-rr-search text-xl text-slate-300 flex"></i>
                                    </div>
                                    <input oninput="searchData('{{ $se->id }}')" type="text" id="dataSearch{{ $se->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quick Search..." required>
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
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="data-container-{{ $se->id }}">

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
            return class_id==0?data:data.section_id == class_id
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
            return id==0?item:item.section_id == id
        })
        .forEach(function(data,index){
            $('#data-container-'+id).append(`
                <tr>
                    <td>${index+1}</td>
                    <td><img class="rounded-circle border" style="height: 40px; width: 40px;" src="${data.user.photo}" alt="photo"></td>
                    <td>${data.user.name}</td>
                    <td>${data.adm_no}</td>
                    <td class="${id!==0?'hidden':''}">{{ $my_class->title}} ${data.section.name}</td>
                    <td>${data.user.email}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left">
                                    <a href="{{ route('students.show','') }}${data.id}" class="dropdown-item"><i class="icon-eye"></i> View Profile</a>
                                    @if(Qs::userIsTeamSA())
                                        <a href="{{ route('students.edit', '') }}${data.id}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                        <a href="{{ route('st.reset_pass','' ) }}${data.user.id}" class="dropdown-item"><i class="icon-lock"></i> Reset password</a>
                                    @endif
                                    <a target="_blank" href="{{ route('marks.year_selector','' ) }}${data.user.id}" class="dropdown-item"><i class="icon-check"></i> Marksheet</a>

                                    {{--Delete--}}
                                    @if(Qs::userIsSuperAdmin())
                                        <a id="${data.user.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                        <form method="post" id="item-delete-${data.user.id}" action="{{ route('students.destroy','' ) }}${data.user.id}" class="hidden">@csrf @method('delete')</form>
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
