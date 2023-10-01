@extends('layouts.master')
@section('page_title', 'School Calendar')
@section('content')
<style>
    #addEvent{
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 3;
    }
    .overlay{
        background: #000;
        opacity: .6;
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 2;
        top: 0;
        left: 0;
        backdrop-filter: blur(.4);
    }
</style>
    <div class="position-relative space-y-4">
        <span class="overlay"></span>
        <div class="card shadow-none">
            <div class="card-header text-secondary bg-secondary-5 header-elements-inline py-3">
                <h6 class="card-title">ADD NEW </h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="">
                <div class="card-body py-4 ">
                    <form action="/setup/calendar/save-academic-year" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" id="" placeholder="2014/2015">
                                <label for="default" class="mt-3">
                                    <input type="hidden" name="default" value="0">
                                    <input type="checkbox" class="" name="default"  onchange="this.value=this.checked?1:0" id="default">
                                    default Calendar for current school
                                </label>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="">From</label>
                                <input type="date" name="start_date" class="form-control" id="">
                            </div>
                            <div class="col-md-4">
                                <label for="">End</label>
                                <input type="date" name="end_date" class="form-control" id="">
                            </div>
                        </div>
                        <div class="mt-3 mr-auto">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-warning">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card" >
            <div class="card-header text-secondary bg-secondary-5 header-elements-inline py-3">
                <h6 class="card-title ">MANAGE CALENDAR EVENTS </h6>
                {!! Qs::getPanelOptions() !!}
            </div>

            <div class="">
                <div class="card-body py-4 ">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div id="addEvent" class=" w-md-50" style="width: 90%;">
            <div class="card">
                <div class="card-header text-dark bg-secondary-5 d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0">Add New Event</h6>
                    <div class="closeBtn border p-1 " onclick="closeModal()">
                        <i class="bi-x-lg text-danger"></i>
                    </div>
                </div>
                <div class="card-body">
                    <form action="/setup/calendar/create-event" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="d-block">Event Title</label>
                            <input type="text" name="title" placeholder="Mid-Term Break" class="form-control w-full" id="">
                        </div>
                        <div class=" mb-3">
                            <label for="" class="d-block">Description</label>
                            <textarea type="text" name="description" class="form-control w-full"></textarea>
                        </div>
                        <div class=" mb-3">
                            <div class="mr-2 mr-md-0">
                                <label for="" class="d-block">Start Date</label>
                                <input type="date" value="" class="form-control w-full" name="start_date" id="startDate">
                            </div>
                            <div class="">
                                <label for="" class="d-block">End Date</label>
                                <input type="date" value="" name="end_date" class="form-control w-full" id="endDate">
                            </div>
                        </div>
                        <div class="">
                            <button class="btn btn-secondary">continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="">
        let modalStartDate=null;
        let modalEndDate = null;
        $('.overlay').hide();
            $('#addEvent').hide();
        function closeModal(){
            $('.overlay').hide();
            $('#addEvent').hide();
        }
        function formatDate(originalDate) {
            // Split the date string into components
            var dateComponents = originalDate.split('/');

            // Rearrange the components to "YYYY-MM-DD" format
            var formattedDate = dateComponents[2] + '-' +
                                (dateComponents[0].length === 1 ? '0' + dateComponents[0] : dateComponents[0]) + '-' +
                                (dateComponents[1].length === 1 ? '0' + dateComponents[1] : dateComponents[1]);

            return formattedDate;
        }
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            selectable:true,
            initialView:'dayGridMonth',
            businessHours: {
                // days of week. an array of zero-based day of week integers (0=Sunday)
                daysOfWeek: [ 1, 2, 3, 4,5 ], // Monday - Thursday
                // startTime: '8:00', // a start time (10am in this example)
                // endTime: '18:00', // an end time (6pm in this example)
            },
            events: [
                @if(Qs::getSchoolCalendarEvents() && count(Qs::getSchoolCalendarEvents()) >= 1)
                    @foreach (Qs::getSchoolCalendarEvents() as $ce )
                        {
                            title:"{{ $ce->title }}",
                            start:"{{ $ce->start_date }}",
                            end:'{{ $ce->end_date }}',
                        },
                    @endforeach
                @endif
            ],
            select: function(info) {
                $('#addEvent').show();
                $('.overlay').show();
                var startDate = formatDate(new Date(info.start).toLocaleDateString());
                var endDate = formatDate(new Date(info.end).toLocaleDateString());
                $('#startDate').val(startDate)
                $('#endDate').val(endDate)
                console.log(startDate)
            }
        });
        calendar.render();
        // start jquery

      });
    </script>
@endsection
