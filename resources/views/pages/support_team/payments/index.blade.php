@extends('layouts.master')
@section('page_title', 'Manage Payments')
@section('content')

<div class="flex flex-col gap-3">
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
            <h5 class="card-title"><i class="icon-cash2 mr-2 "></i> Select year</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('payments.select_year') }}">
                @csrf
                <div class="">
                    <div class="md:w-7/12 mx-auto">
                    <label for="year" class="col-form-label font-weight-bold">Select Year <span class="text-danger">*</span></label>
                        <div class="flex items-center gap-3 justify-between">
                            <div class="flex-1">
                                <div class="">
                                    <select data-placeholder="Select Year" required id="year" name="year" class=" select w-full">
                                        @foreach($years as $yr)
                                            <option {{ ($selected && $year == $yr->id) ? 'selected' : '' }} value="{{ $yr->id }}">{{ $yr->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="">
                                <div class="">
                                    <button type="submit" class="btn btn-primary w-full">Submit <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @if($selected)
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Payments for {{ Qs::findAcademicYearById($year)[0]->title }} Session</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-payments" class="nav-link active" data-toggle="tab">All Classes</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Class Payments</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach($my_classes as $mc)
                            <a href="#pc-{{ $mc->id }}" onclick="getData('{{$mc->id}}')" class="dropdown-item" data-toggle="tab">{{ $mc->title }}</a>
                        @endforeach
                    </div>
                </li>
            </ul>

            <div class="tab-content">
                    <div class="tab-pane fade show active overflow-x-auto" id="all-payments">
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
                        <table class="table table-auto filterable-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Ref_No</th>
                                <th>Class</th>
                                <th>Method</th>
                                <th>Info</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-0">

                            </tbody>
                        </table>
                    </div>

                @foreach($my_classes as $mc)
                    <div class="tab-pane fade overflow-x-auto" id="pc-{{ $mc->id }}">
                    <div class="search py-3">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                    <i class="fi fi-rr-search text-xl text-slate-300 flex"></i>
                                </div>
                                <input oninput="searchData('{{$mc->id}}')" type="text" id="dataSearch{{$mc->id}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quick Search..." required>
                            </div>
                        </form>
                    </div>

                    <div class="table overflow-x-auto">
                        <table class="table table-auto">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Ref_No</th>
                                <th>Class</th>
                                <th>Method</th>
                                <th>Info</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-{{$mc->id}}">

                            </tbody>
                        </table>
                    </div>

                    </div>
                    @endforeach
            </div>
        </div>
    </div>
    @endif
</div>


    {{--Payments List Ends--}}
<script>
@if($selected)
$(document).ready()
{
    const paymentRecords = {!! $payments->toJson() !!};
    function searchData(class_id){
        const searchInput = $('#dataSearch'+class_id)
        const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
        // Filter records based on the search
        const filteredRecords = paymentRecords
        .filter((data)=>{
            return class_id==0?data:data.my_class_id == class_id
        })
        .filter(function (data) {
            return data.title.toLowerCase().includes(searchTerm); // Replace "someProperty" with the property you want to search
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
                    <td>${index+1}</td>
                    <td>${data.title}</td>
                    <td>${data.amount}</td>
                    <td>${data.ref_no}</td>
                    <td>${data.my_class_id?data.my_class.name:''}</td>
                    <td>${data.method}</td>
                    <td>${data.description}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            <div class="dropdown">
                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left">
                                    {{--Edit--}}
                                    <a href="{{ route('payments.edit', '') }}${data.id}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                    {{--Delete--}}
                                    <a id="${data.id}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                    <form method="post" id="item-delete-${data.id}" action="{{ route('payments.destroy', '') }}${data.id}" class="hidden">@csrf @method('delete')</form>
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
        display(paymentRecords,id)
    }
    getData();
    // Call the display function to initially display the records
};
@endif
</script>
@endsection
