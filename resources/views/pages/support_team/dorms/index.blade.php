@extends('layouts.master')
@section('page_title', 'Manage Dorms')
@section('content')

    <div class="">

        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-dorms" class="nav-link active" data-toggle="tab">Manage Dorms</a></li>
            <li class="nav-item"><a href="#new-dorm" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Dorm</a></li>
        </ul>

        <div class="tab-content p-md-4 border-b border-l border-r bg-white">
                <div class="tab-pane fade show active" id="all-dorms">
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
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-0">
                            </tbody>
                        </table>
                    </div>

                </div>

            <div class="tab-pane fade" id="new-dorm">

                <div class="row">
                    <div class="col-md-12">
                        <form class="ajax-store" method="post" action="{{ route('dorms.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of Dormitory">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                                <div class="col-lg-9">
                                    <input name="description" value="{{ old('description') }}"  type="text" class="form-control" placeholder="Description of Dormitory">
                                </div>
                            </div>
                            <input type="hidden" name="school_id" value="{{Qs::findActiveSchool()[0]->id}}">
                            <div class="text-right">
                                <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Dorm List Ends--}}
    <script>
        $(document).ready()
        {
            // for updating records
            var records = {!! $dorms->toJson() !!};
            records = Object.values(records);
            function searchData(class_id){
                const searchInput = $('#dataSearch'+class_id)
                const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
                // Filter records based on the search
                const filteredRecords = records
                .filter((data)=>{
                    return class_id==0?data:data.my_class_id == class_id
                })
                .filter(function (data) {
                    return data.name.toLowerCase().includes(searchTerm) || data.description.toLowerCase().includes(searchTerm) ; // Replace "someProperty" with the property you want to search
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
                            <td>${data.name}</td>
                            <td>${data.description}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            @if(Qs::userIsTeamSA())
                                            {{--Edit--}}
                                            <a href="/dorms/${data.id}/edit" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                            @endif
                                                @if(Qs::userIsSuperAdmin())
                                            {{--Delete--}}
                                            <a id="${data.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                            <form method="post" id="item-delete-${data.id}" action="{{ route('dorms.destroy', '') }}/${data.id}" class="hidden">@csrf @method('delete')</form>
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
