@extends('layouts.master')
@section('page_title', 'Select Student Marksheet')
@section('content')
<div class="space-y-3">
    <div class="card shadow-none border border-purple-800/20">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h5 class="card-title text-black flex items-center"><i class="icon-books mr-2 flex text-slate-800"></i> Select Student Marksheet</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
                <form method="post" action="{{ route('marks.bulk_select') }}">
                    @csrf
                    <div class="row space-y-3">
                        <div class="">
                            <fieldset>

                                <div class="grid md:grid-flow-col-dense flex-wrap items-end gap-3 ">
                                    <div class="">
                                        <div class="">
                                            <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                            <select required onchange="getClassSections(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                                <option value="">Select Class</option>
                                                @foreach($my_classes as $c)
                                                    <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="">
                                            <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                                            <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                        @if($selected)
                                            @foreach($sections as $s)
                                                    <option {{ ($section_id == $s->id ? 'selected' : '') }} value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                            @endif
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                        </div>
                        <div class="">
                            <div class="">
                                <button type="submit" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2  focus:outline-none flex items-center">View Marksheets <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </div>

                </form>
        </div>
    </div>
    @if($selected)
    <div class="card shadow-none  border border-purple-800/20">
        <div class="card-body">
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
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="data-container-0">
                    </tbody>
                </table>                
            </div>

        </div>
    </div>
    @endif    
</div>

<script>
    $(document).ready()
    {
        // for updating records
        var records = {!! $selected?$students->toJson():null !!}
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
                return data.user.name.toLowerCase().includes(searchTerm)  // Replace "someProperty" with the property you want to search
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
                        <td><img class="rounded-circle border" style="height: 40px; width: 40px;" src="${data.user.photo}" alt="photo"></td>
                        <td class="whitespace-nowrap">${data.user.name}</td>
                        <td>${data.adm_no}</td>
                        <td><a class="btn btn-danger whitespace-nowrap" href="/marks/select_year/${btoa(data.user_id)}">View Marksheet</a></td>
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
