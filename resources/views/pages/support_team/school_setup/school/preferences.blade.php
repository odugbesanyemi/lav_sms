@extends('layouts.master')
@section('page_title', 'system preferences')
@section('content')
<div>
    <div class="card shadow-none" id="app">
        <div class="card-header text-secondary bg-secondary-5 header-elements-inline py-3">
            <h6 class="card-title ">MAINTENANCE MODE</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="">
            <div class="card-body py-4 ">
                <!-- <h4>Welcome to the Maintenance Section of our website's admin panel</h4>
                <span class="text-secondary">This section provides you with tools to manage and monitor the ongoing maintenance activities for the site.</span> -->
                <div class="switchElement d-flex gap-2 align-items-center justify-content-between mt-1">

                    <h2 class=" pl-2 fs-1 text-secondary" >Maintenance is {{ $data[0]->maintenance_status == 1 ?'on':'off' }} </h2>
                    <div class="toggle">
                        <input onchange="updatePreference(['maintenance_status',this.checked==true?1:0])" type="checkbox"  class="check" {{ $data[0]->maintenance_status == 1 ?'checked':'' }}>
                        <b class="b switch"></b>
                        <b class="b track"></b>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mt-3 ">
                    <div class="row">
                        <div class="col-md-4">
                            <h4> Maintenance Message </h4>
                            <span class="text-secondary">This is the information displayed to users of this school during maintenance.</span>
                        </div>
                        <div class="col-md-8 p-2">
                            <div id="editor">
                                {{ $data[0]->maintenance_message }}
                            </div>
                            <button onclick="updatePreference(['maintenance_message',document.querySelector('#editor').textContent])" class="btn btn-secondary mt-2 w-7">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-none" id="app">
        <div class="card-header text-secondary bg-secondary-5 header-elements-inline py-3">
            <h6 class="card-title">NOTIFICATIONS</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="">
            <div class="card-body py-3">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="">
                            <h4> Email Notifications </h4>
                            <span class="text-secondary">Allows you to recieve Email Notifications and Alerts for User operations?.</span>
                    </div>
                    <div>
                        <div class="toggle">
                            <input type="checkbox" onchange="updatePreference(['allow_email',this.checked==true?1:0])" class="check" id="email_check" {{ $data[0]->allow_email == 1 ?'checked':'' }}>
                            <b class="b switch"></b>
                            <b class="b track"></b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <div class="">
                            <span class="text-secondary">Add Email to receive alerts and notifications for current school</span>
                    </div>
                    <div>
                        <input type="email" onchange="updatePreference(['notify_email',this.value])" value="{{$data[0]->notify_email}}" id="email_input" class=" p-2 w-100" placeholder="name@schoolemailhost">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card shadow-none" id="app">
        <div class="card-header text-secondary bg-secondary-5 header-elements-inline py-3">
            <h6 class="card-title">ATTENDANCE</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="">
            <div class="card-body py-3">
                <div class="d-flex flex-column align-items-start justify-content-between">
                    <div class="">
                            <h4> Set Half Day and Full-Day Minutes </h4>
                            <span class="text-secondary">Configure how attendance is tracked and reported?.</span>
                    </div>
                    <div class="mt-3">
                        <form action="" class="d-flex">
                            <fieldset class="mr-md-3 mr-1">
                                <label for="">Half Day Minutes:</label>
                                <input onchange="updatePreference(['half_day_minutes',this.value])" type="number" name="" value="{{$data[0]->half_day_minutes}}" id="" class="form-control">
                            </fieldset>
                            <fieldset>
                                <label for="">Full Day Minutes:</label>
                                <input onchange="updatePreference(['full_day_minutes',this.value])" type="number" name="" id="" value="{{$data[0]->full_day_minutes}}" class="form-control">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <!-- -->
        </div>

    </div>
</div>
<script>
    function checkEmailChecked(){
        if ($('#email_check').prop('checked')) {
            $('#email_input').prop('disabled', false);
        } else {
            $('#email_input').prop('disabled', true);
        }
    }
    function updatePreference(arr){
        var key= arr[0]
        var value=arr[1]
        var data = {
            key:key,
            value:value
        }
        console.log(data)
        // loader starts
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:`/setup/schools/update_school_preference/${data.key}/${data.value}/?_token=${csrfToken}`,
            method:'post',
            success:function(){
                location.reload()
                // do nothing
                // loaderends
            }
        })
    }
    $(document).ready()
    {
        checkEmailChecked();

    }
</script>
@endsection
