@extends('layouts.master')
@section('page_title', 'Manage Class Sections')
@section('content')

    <div class="">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#new-section" class="nav-link active" data-toggle="tab">Create New Section</a></li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Sections</a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($my_classes as $c)
                        <a href="#c{{ $c->id }}" onclick="getData('{{ $c->id }}')" class="dropdown-item" data-toggle="tab">{{ $c->title }}</a>
                    @endforeach
                </div>
            </li>
        </ul>

        <div class="tab-content md:p-4 border-r border-l border-b bg-white">
            <div class="tab-pane show max-md:p-2 active fade" id="new-section">
                <div class="row">
                    <div class="col-md-12">
                        <form class="ajax-store" method="post" action="{{ route('sections.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of Section">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Select Class <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select required data-placeholder="Select Class" class="form-control select" name="my_class_id" id="my_class_id">
                                        @foreach($my_classes as $c)
                                            <option {{ old('my_class_id') == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Teacher</label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Teacher" class="form-control select-search" name="teacher_id" id="teacher_id">
                                        <option value=""></option>
                                        @foreach($teachers as $t)
                                            <option {{ old('teacher_id') == Qs::hash($t->id) ? 'selected' : '' }} value="{{ Qs::hash($t->id) }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="school_id" value="{{ Qs::findActiveSchool()[0]->id }}">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @foreach($my_classes as $d)
                <div class="tab-pane fade " id="c{{ $d->id }}">
                    <div class="search py-3 max-md:px-2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                    <i class="fi fi-rr-search text-xl text-slate-300 flex"></i>
                                </div>
                                <input oninput="searchData('{{ $d->id }}')" type="text" id="dataSearch{{ $d->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quick Search..." required>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Teacher</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-{{ $d->id }}">
                            </tbody>
                        </table>
                    </div>

                </div>
            @endforeach

        </div>
    </div>

    {{--Section List Ends--}}
    <script>
        $(document).ready()
        {
            // for updating records
            var records = {!! $sections->toJson() !!};
            records = Object.values(records);
            function searchData(class_id){
                const searchInput = $('#dataSearch'+class_id)
                const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
                // Filter records based on the search
                const filteredRecords = records
                .filter((data)=>{
                    return class_id==0?data:data.my_class.id == class_id
                })
                .filter(function (data) {
                    return data.name.toLowerCase().includes(searchTerm) || data.my_class.title.toLowerCase().includes(searchTerm) ; // Replace "someProperty" with the property you want to search
                });

                // Update the displayed records
                display(filteredRecords,class_id);
            }
            function display(records,id){

                $('#data-container-'+id).empty(); // Clear previous results
                // filter records based on id
                records
                .filter((item)=>{
                    return id==0?item:item.my_class.id == id
                })
                .forEach(function(data,index){
                    $('#data-container-'+id).append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${data.name}</td>
                            <td>${data.my_class.title}</td>

                            @if('${data.teacher_id}')
                            <td><a target="_blank" href="{{ route('users.show', '') }}/${data.teacher_id}">${data.teacher.name}</a></td>
                                @else
                                <td> - </td>
                            @endif

                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{--edit--}}
                                            @if(Qs::userIsTeamSA())
                                                <a href="/sections/${data.id}/edit" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                            @endif
                                            {{--Delete--}}
                                            @if(Qs::userIsSuperAdmin())
                                                <a id="${data.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                <form method="post" id="item-delete-${data.id}" action="{{ route('sections.destroy', '') }}/${data.id}" class="hidden">@csrf @method('delete')</form>
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
