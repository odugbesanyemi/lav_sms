@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

        <div class="">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-grades" class="nav-link active" data-toggle="tab">Manage Grades</a></li>
                <li class="nav-item"><a href="#new-grade" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Add Grade</a></li>
            </ul>

            <div class="tab-content p-md-4 border-l border-r border-b bg-white">
                <div class="tab-pane fade show active" id="all-grades">
                    <div class="search py-3 max-md:px-2">
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
                                <th>Range</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-0">
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="tab-pane fade" id="new-grade">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                                <span>If The grade you are creating applies to all class types </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('grades.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="{{ old('name') }}" required type="text" class="form-control text-uppercase" placeholder="E.g. A1">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Mark From <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input min="0" max="100" name="mark_from" value="{{ old('mark_from') }}" required type="number" class="form-control" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Mark To <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input min="0" max="100" name="mark_to" value="{{ old('mark_to') }}" required type="number" class="form-control" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Remark</label>
                                    <div class="col-lg-9">
                                        <select class="form-control select" name="remark" id="remark">
                                            <option value="">Select Remark...</option>
                                            @foreach(Mk::getRemarks() as $rem)
                                                <option {{ old('remark') == $rem ? 'selected' : '' }} value="{{ $rem }}">{{ $rem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="school_id" value="{{Qs::findActiveSchool()[0]->id}}">

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{--Class List Ends--}}
<script>
    $(document).ready()
    {
        // for updating records
        var records = {!! $grades->toJson() !!};
        records = Object.values(records);
        console.log(records)
        function searchData(class_id){
            const searchInput = $('#dataSearch'+class_id)
            const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
            // Filter records based on the search
            const filteredRecords = records
            .filter((data)=>{
                return class_id==0?data:data.my_class_id == class_id
            })
            .filter(function (data) {
                return data.name.toLowerCase().includes(searchTerm) || data.remark.toLowerCase().includes(searchTerm) ; // Replace "someProperty" with the property you want to search
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
                        <td>${data.mark_from}-${data.mark_to}</td>
                        <td>${data.remark}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        @if(Qs::userIsTeamSA())
                                        {{--Edit--}}
                                        <a href="/grades/${data.id}/edit" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                    @endif
                                        @if(Qs::userIsSuperAdmin())
                                        {{--Delete--}}
                                        <a id="${data.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                        <form method="post" id="item-delete-${data.id}" action="{{ route('grades.destroy', '') }}/${data.id}" class="hidden">@csrf @method('delete')</form>
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
