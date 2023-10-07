@extends('layouts.master')
@section('page_title', 'New School')
@section('content')


    <div >
        <div class="card-body bg-white">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#new-school" class="nav-link active" data-toggle="tab">Create New School</a></li>
                <li class="nav-item" id="viewSchool"><a href="#view" class="nav-link" data-toggle="tab" >View Schools</a></li>
            </ul>

            <div class="tab-content md:p-4 border-b border-l border-r border-t-0">
                <div class="tab-pane fade show active" id="new-school">
                    <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-store" action="{{route('setup.schools.new')}}" data-fouc>
                        @csrf
                        <h6>General Data</h6>
                        <fieldset>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="user_type"> School Name: <span class="text-danger">*</span></label>
                                        <input value="" required type="text" name="name" placeholder="School Name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md">
                                    <div class="form-group">
                                        <label>Address: <span class="text-danger">*</span></label>
                                        <input value="" required type="text" name="address" placeholder="Address" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email address:<span class="text-danger">*</span> </label>
                                        <input value="" type="email" name="email" class="form-control" placeholder="school@email.com">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Principal: <span class="text-danger">*</span></label>
                                        <input value="" type="text" name="principal" class="form-control" required placeholder="Principal Full name">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Phone:<span class="text-danger">*</span></label>
                                        <input value="{{ old('phone') }}" type="text" name="phone" required class="form-control" placeholder="+2341234567" >
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Telephone:</label>
                                        <input value="{{ old('phone2') }}" type="text" name="telephone" class="form-control" placeholder="+2341234567" >
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Generic Name:<span class="text-danger">*</span> </label>
                                        <input value="" type="text" name="generic_name" class="form-control" placeholder="School College">
                                    </div>
                                </div>
                                {{-- Nationality --}}
                                <div class="col-md-3">
                                    <label for="state_id">Nationality: </label>
                                    <select  data-placeholder="Choose.." class="select-search form-control" name="nationality" id="nationality_id">
                                        <option value=""></option>
                                        @foreach($nationals as $nt)
                                            <option value="{{ $nt->id }}">{{ $nt->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--State--}}
                                <div class="col-md-3">
                                    <label for="state_id">State: </label>
                                    <select onchange="getLGA(this.value)"  data-placeholder="Choose.." class="select-search form-control" name="state" id="state_id">
                                        <option value=""></option>
                                        @foreach($states as $st)
                                            <option {{ (old('state_id') == $st->id ? 'selected' : '') }} value="{{ $st->id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--LGA--}}
                                <div class="col-md-3">
                                    <label for="lga_id">LGA: </label>
                                    <select  data-placeholder="Select State First" class="select-search form-control" name="lga" id="lga_id">
                                        <option value=""></option>
                                    </select>
                                </div>

                            </div>

                            <div class="row mt-3 d-flex align-items-center ">

                                {{-- options --}}
                                <!-- <div class="col-md-4">
                                    <input type="checkbox" name="active" id="setActive">
                                    <label for="setActive">set as Active</label>
                                </div> -->
                                {{--PASSPORT--}}
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="d-block">Upload school logo:</label>
                                        <input value="" accept="image/*" type="file" name="photo" class="" data-fouc>
                                        <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                    </form>
                </div>
                <div class="tab-pane fade" id="view">
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
                                <th>Address</th>
                                <th>Email</th>
                                <th>Principal</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="data-container-0">


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready()
        {
            $('#viewSchool').click(function(){
                // location.reload();
            })
            var records = {!! $schools->toJson() !!};
            function searchData(class_id){
                const searchInput = $('#dataSearch'+class_id)
                const searchTerm = $('#dataSearch'+class_id).val().toLowerCase();
                // Filter records based on the search
                const filteredRecords = records
                .filter((data)=>{
                    return class_id==0?data:data.my_class_id == class_id
                })
                .filter(function (data) {
                    return data.name.toLowerCase().includes(searchTerm) || data.generic_name.toLowerCase().includes(searchTerm) ; // Replace "someProperty" with the property you want to search
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
                            <td>${data.address}</td>
                            <td>${data.email}</td>
                            <td>${data.principal}</td>
                            <td>${data.phone}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left">
                                            @if(Qs::userIsSuperAdmin())
                                                {{--Delete--}}
                                                <a id="{{ Qs::hash('${data.id}') }}"  onclick="confirmDelete(this.id)" href="#" class="dropdown-item  ${data.active==1?'disabled-link':''}"><i class="icon-trash"></i> Delete</a>
                                                <form method="post" id="item-delete-{{ Qs::hash('${data.id}') }}" action="/setup/schools/remove/{{ Qs::hash('${data.id}') }}" class="hidden">@csrf @method('delete')</form>
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
