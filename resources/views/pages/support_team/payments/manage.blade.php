@extends('layouts.master')
@section('page_title', 'Student Payments')
@section('content')
<div class="flex flex-col gap-3">
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-dark">
            <h5 class="card-title"><i class="icon-cash2 mr-2"></i> Student Payments</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('payments.select_class') }}">
                @csrf
              <div class="">
                  <div class="md:w-7/12 mx-auto">
                  <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                      <div class="flex items-center gap-3 justify-between">
                          <div class="flex-1">
                              <div class="">
                                  <select required id="my_class_id" name="my_class_id" class="form-control select">
                                      <option value="">Select Class</option>
                                      @foreach($my_classes as $c)
                                          <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->title }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>

                          <div class="">
                              <div class="">
                                  <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
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
                <table class="w-full table">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>ADM_No</th>
                        <th>Payments</th>
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
@if($selected)
$(document).ready()
{
    var studentRecords = {!! $students->toJson() !!};
    studentRecords = Object.values(studentRecords)
    function searchData(class_id){
        const searchInput = $('#dataSearch'+class_id)
        const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
        // Filter records based on the search
        const filteredRecords = studentRecords
        .filter((data)=>{
            return class_id==0?data:data.my_class_id == class_id
        })
        .filter(function (data) {
            return data.user.name.toLowerCase().includes(searchTerm); // Replace "someProperty" with the property you want to search
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
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="${data.user.photo}" alt="photo"></td>
                        <td>${data.user.name}</td>
                        <td>${data.adm_no}</td>
                        <td>
                            <div class="dropdown">
                                <a href="#" class=" btn btn-danger" data-toggle="dropdown"> Manage Payments <i class="icon-arrow-down5"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left">
                                    <a href="/payments/invoice/${data.user_id}" class="dropdown-item">All Payments</a>
                                    @foreach(Pay::getYears('${data.user_id}') as $py)
                                    @php
                                        echo $py
                                    @endphp
                                    @if($py)
                                        <a href="{{ route('payments.invoice', [Qs::hash('${data.user_id}'), $py]) }}" class="dropdown-item">{{ $py }}</a>
                                    @endif
                                    @endforeach
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
        display(studentRecords,id)
    }
    getData();
    // Call the display function to initially display the records
};
@endif
    </script>
@endsection
