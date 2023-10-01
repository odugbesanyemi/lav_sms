@extends('layouts.master')
@section('page_title', 'Fix Mark Errors')
@section('content')
    <div class="card shadow-none">
        <div class="card-header header-elements-inline py-3 bg-body-tertiary text-secondary">
            <h5 class="card-title"><i class="icon-wrench mr-2"></i> Batch Fix </h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form class="ajax-update" method="post" action="{{ route('marks.batch_update') }}">
                @csrf @method('PUT')
                <div class="space-y-3">
                    <div class="">
                        <fieldset>

                            <div class="grid lg:grid-flow-col-dense md:grid-cols-2 flex-wrap items-end gap-3">
                                <div class="">
                                    <div class="">
                                        <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                                        <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select">
                                            @foreach($exams as $ex)
                                                <option {{ $selected && $exam_id == $ex->id ? 'selected' : '' }} value="{{ $ex->id }}">{{ $ex->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="">
                                        <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                        <select required onchange="getClassSections(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                            <option value="">Select Class</option>
                                            @foreach($my_classes as $c)
                                                <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="">
                                        <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                                        <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                            @if($selected)
                                                @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                                    <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
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
                            <button type="submit" class="btn btn-danger">Fix Errors <i class="icon-wrench2 ml-2"></i></button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
