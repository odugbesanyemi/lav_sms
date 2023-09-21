<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="CJ Inspired">

    <title> @yield('page_title') | {{ config('app.name') }} </title>

    @include('partials.inc_top')
</head>
<style>
    .content-wrapper, .sidebarMenu{
        background-image: url('{{ asset("global_assets/images/backgrounds/bg_pattern.jpeg") }}');
        background-color: rgba(255, 255, 255, 1);
        background-size:250px 250px;

    }
    .content{
        background-color: rgba(255, 255, 255, .6);
    }
    .content::-webkit-scrollbar,.content::-webkit-scrollbar-track,.content::-webkit-scrollbar-thumb,.sidebarMenu::-webkit-scrollbar,.content::-webkit-scrollbar-track,.content::-webkit-scrollbar-thumb{
        display: none !important;
    }
    .card-title{
        margin: 0;
    }
    @media (min-width:768px) {

        .fc-header-toolbar{
            display: none !important;
        }
    }
</style>
<body id="app" class="overflow-y-hidden {{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.show', 'ttr.manage', 'ttr.show']) ? 'sidebar-xs' : '' }}">
    @include('partials.top_menu')
<div class="d-flex relative overflow-x-auto">
    <div class="sidebar-menu d-md-block d-none" id="sidebar-collapsible">
        @include('partials.menu')
    </div>
    <div class="bg-body-tertiary flex-1" >
        <div class="content-wrapper position-relative"  >
            @include('partials.header')
            <div class="content p-md-5 overflow-auto" style="height:calc( 100vh - 128.05px);" >
                {{--Error Alert Area--}}
                @if($errors->any())
                    <div class="alert alert-danger border-0 alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            @foreach($errors->all() as $er)
                                <span><i class="icon-arrow-right5"></i> {{ $er }}</span> <br>
                            @endforeach

                    </div>
                @endif
                <div id="ajax-alert" style="display: none"></div>
                <div id="ajax-loader" style="display:none">
                    <img class="w-24 fixed top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 z-50 mix-blend-multiply" src="{{asset('global_assets/images/Loading_2.gif')}}" alt="">
                </div>
                @yield('content')
            </div>


        </div>
    </div>
</div>

@include('partials.inc_bottom')

@yield('scripts')
</body>
</html>
