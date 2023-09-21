@extends('layouts.master')
@section('page_title', 'Marks Preferences')
@section('content')
@php
function getOrdinal($number) {
    if ($number % 10 == 1 && $number % 100 != 11) {
        return $number . 'st';
    } elseif ($number % 10 == 2 && $number % 100 != 12) {
        return $number . 'nd';
    } elseif ($number % 10 == 3 && $number % 100 != 13) {
        return $number . 'rd';
    } else {
        return $number . 'th';
    }
}
@endphp


<style>
    .edit_input{
        border: 0;
        padding: 5px;
        width: 100% !important;
        background-color: white !important;
    }
    .edit_input:focus{
        border: 0;
        outline: 0;
        border-bottom: 1px solid gainsboro;

    }
    .edit_input_show{
        padding: 5px;
    }
    .edit_input_show:focus{
        outline: 0;
    }
</style>
    <div class="card shadow-none">
        <div class="card-header header-elements-inline bg-body-tertiary text-secondary py-3">
            <h6 class="card-title ">Select Marking Period</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <!-- <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#page1" class="nav-link active" data-toggle="tab">Preferences</a></li>
            </ul> -->

            <div class="tab-content p-md-4">
                <div class="tab-pane fade show active p-0" id="page1">

                    <div class="card-body">
                        <form action="/marks/setup/preferences-select" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <select name="marking_period_id" id="chooseMP" class="form-control large">
                                    <option value="">Choose Marking Period</option>
                                        @foreach ($markingPeriods as $mp )
                                            <option {{$selected && $markingPeriod[0]->id==$mp->id ?'selected':''}} value="{{ $mp->id }}">{{$mp->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" disabled placeholder="selected Type" value="{{$selected? $markingPeriod[0]->mp_type:''}}">
                                </div>
                                <button type="submit" class="col-md-3 ml-auto btn btn-primary">continue</button>
                            </div>
                        </form>
                    </div>
                    @if ($selected)
                    <form action="/marks/setup/update-preferences/{{$markingPeriod[0]->id}}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <h6 class="col-md-12 py-2 mb-3 text-secondary">FINAL MARKS</h6>
                                <div class="col-md-12 d-flex mb-2 align-items-center justify-content-between">
                                    <h6 class="m-0 mr-2">CA</h6>
                                    <input type="number" name="ca_final_score" value="{{ $currMp->ca_final_score?$currMp->ca_final_score:0}}" class="form-control col-4" id="">
                                </div>
                                <div class="col-md-12 d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 mr-2">EXAM</h6>
                                    <input type="number" name="exam_final_score" class="form-control col-4" value="{{ $currMp->exam_final_score?$currMp->exam_final_score:0}}"  id="">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="alert alert-secondary mb-3 col-md-12" role="alert">
                                    show Reports skills from Report Sheet E.g Psychomotor, Affective e.t.c for this marking period
                                </div>
                                <div class="col-md-12 d-flex mb-2 align-items-center justify-content-between">
                                    <h6 class="m-0 mr-2">Show Report skills</h6>
                                    <div class="toggle">
                                        <input value="{{$currMp->show_skills}}" onchange="this.value=this.checked==true?1:0;updateRecord(this,'{{$markingPeriod[0]->id}}')"  name="show_skills"  type="checkbox" {{$currMp->show_skills==1?'checked':''}}  class="check">
                                        <b class="b switch"></b>
                                        <b class="b track"></b>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="alert alert-secondary mb-3 col-md-12" role="alert">
                                    select the order of Marking periods for this type e.g Term or Quarter. result is either First Term, Second Term or Mid Term or Final Term
                                </div>
                                <div class="col-md-12 d-flex mb-2 align-items-center justify-content-between">
                                    <h6 class="m-0 mr-2">Marking Period Type Position</h6>
                                    <select name="type_order" class="form-control col-4" id="">
                                        @foreach ($markingPeriods->where('mp_type',$markingPeriod[0]->mp_type)->where('parent_id',$markingPeriod[0]->parent_id) as $mp)
                                            <option {{ $currMp->type_order == $loop->iteration?'selected':'' }} value="{{$loop->iteration}}">{{ getOrdinal($loop->iteration) }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-success btn-lg" type="submit">Continue</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else

                    @endif


                </div>
            </div>
        </div>
    </div>
    <script>
            function updateRecord(element,id){
                var title = ($(element).attr('name'))
                var value = ($(element).val())
                var data = {
                    [title]:value
                }
                console.log(data)
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var url = `/marks/setup/update-preferences/${id}`
                console.log(url)
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        data: data
                    },
                    success: () => {
                        flash({msg : 'skill updated Successfully', type : 'success'});
                    }
                });
            }
            function deleteRecord(id){
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var url = `/marks/setup/delete/${id}`
                $.ajax({
                    url: url,
                    method: 'delete',
                    data: {
                        _token: csrfToken,
                    },
                    success: () => {
                        flash({msg : 'Skill Deleted Successfully', type : 'success'});
                        location.reload();
                    }
                });
            }
    </script>

@endsection
