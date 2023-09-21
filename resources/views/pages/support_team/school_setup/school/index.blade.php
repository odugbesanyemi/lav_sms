@extends('layouts.master')
@section('page_title', 'School Information')
@section('content')


    <div class="card shadow-none" >
        <div class="card-header bg-secondary-5 text-secondary header-elements-inline py-3">
            <h6 class="card-title"></h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#school" class="nav-link active" data-toggle="tab">School Information</a></li>
                <!-- <li class="nav-item" id="viewSchool"><a href="#view" class="nav-link" data-toggle="tab" >View Schools</a></li> -->
            </ul>

            <div class="tab-content p-md-4">
                <div class="tab-pane fade show active" id="school">
                    <form method="post" enctype="multipart/form-data" class="wizard-form steps-validation ajax-store" action="/setup/schools/update/{{$activeSchool[0]->id}}" data-fouc>
                        @csrf
                        <h6>General Data</h6>
                        <fieldset>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="user_type"> School Name: <span class="text-danger">*</span></label>
                                        <input value="{{$activeSchool[0]->name}}" required type="text" name="name" placeholder="School Name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md">
                                    <div class="form-group">
                                        <label>Address: <span class="text-danger">*</span></label>
                                        <input value="{{$activeSchool[0]->address}}" required type="text" name="address" placeholder="Address" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email address:<span class="text-danger">*</span> </label>
                                        <input value="{{$activeSchool[0]->email}}" type="email" name="email" class="form-control" placeholder="school@email.com">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Principal: <span class="text-danger">*</span></label>
                                        <input value="{{$activeSchool[0]->principal}}" type="text" name="principal" class="form-control" required placeholder="Principal Full name">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Phone:<span class="text-danger">*</span></label>
                                        <input value="{{$activeSchool[0]->phone}}" type="text" name="phone" required class="form-control" placeholder="+2341234567" >
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Telephone:</label>
                                        <input value="{{$activeSchool[0]->telephone}}" type="text" name="telephone" class="form-control" placeholder="+2341234567" >
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Generic Name:<span class="text-danger">*</span> </label>
                                        <input value="{{$activeSchool[0]->generic_name}}" type="text" name="generic_name" class="form-control" placeholder="School College">
                                    </div>
                                </div>
                                {{-- Nationality --}}
                                <div class="col-md-3">
                                    <label for="state_id">Nationality: </label>
                                    <select  data-placeholder="Choose.." class="select-search form-control" name="nationality" id="nationality_id">
                                        <option value="">{{$activeSchool[0]->nationality}}</option>
                                        @foreach($nationals as $nt)
                                            <option {{ $activeSchool[0]->nationality == $nt->id ? 'selected' : '' }} value="{{ $nt->id }}">{{ $nt->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--State--}}
                                <div class="col-md-3">
                                    <label for="state_id">State: </label>
                                    <select onchange="getLGA(this.value)"  data-placeholder="Choose.." class="select-search form-control" name="state" id="state_id">
                                        <option value=""></option>
                                        @foreach($states as $st)
                                            <option {{ $activeSchool[0]->state == $st->id ? 'selected' : '' }} value="{{ $st->id }}">{{ $st->name }}</option>
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

                                {{--PASSPORT--}}
                                <!-- <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="d-block">Upload school logo:</label>
                                        <input value="" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                        <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                                    </div>
                                </div> -->
                            </div>

                        </fieldset>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#viewSchool').click(function(){
                // location.reload();
            })
        })
    </script>

@endsection
