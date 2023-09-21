@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')
<style>
    .fc-header-toolbar{
        display: flex !important;
    }
    .fc-header-toolbar h2{
        font-size: 20px !important;
    }
    .fc-header-toolbar {
        padding: 0 !;
    }
</style>
    @if(Qs::userIsTeamSA())
       <div class="row mb-4 g-3">
           <div class="col-sm-6 col-xl-3">
               <div class="card-body border-0 bg-blue-500 has-bg-image h-100  d-flex align-items-center justify-content-center">
                   <div class="w-100 d-flex justify-content-between">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'student')->count() }}</h3>
                           <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users4 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card-body bg-red-500 has-bg-image border-0 h-100  d-flex align-items-center justify-content-center">
                   <div class="d-flex justify-content-between w-100">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'teacher')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Teachers</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users2 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card-body bg-green-500 has-bg-image border-0 h-100  d-flex align-items-center justify-content-center">
                   <div class="w-100 d-flex justify-content-between">
                       <div class="mr-3 align-self-center">
                           <i class="icon-pointer icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'admin')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Administrators</span>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card-body bg-indigo-500 has-bg-image border-0 h-100 d-flex align-items-center justify-content-center">
                   <div class="w-100 d-flex justify-content-between">
                       <div class="mr-3 align-self-center">
                           <i class="icon-user icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'parent')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Parents</span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       @endif

    {{--Events Calendar Begins--}}
    <div class="card border-1 border shadow-none">
        <div class="card-header p-3 header-elements-inline bg-body-tertiary text-secondary">
            <h5 class="card-title">School Events Calendar</h5>
         {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
    {{--Events Calendar Ends--}}

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
                @if(Qs::getSchoolCalendarEvents()->count() >= 1)
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
            }
        });
        calendar.render();
        // start jquery

      });
    </script>
    @endsection
