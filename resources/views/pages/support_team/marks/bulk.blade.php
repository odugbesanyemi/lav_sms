@extends('layouts.master')
@section('page_title', 'Select Student Marksheet')
@section('content')
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Select Student Marksheet</h5>
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
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:outline-blue-200 outline-2">View Marksheets <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>
                    </div>

                </form>
        </div>
    </div>
    @if($selected)
    <div class="card shadow-none">
        <div class="card-body">
            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>ADM_No</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ $s->user->photo }}" alt="photo"></td>
                        <td>{{ $s->user->name }}</td>
                        <td>{{ $s->adm_no }}</td>
                        <td><a class="btn btn-danger" href="{{ route('marks.year_select', Qs::hash($s->user_id)) }}">View Marksheet</a></td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection
