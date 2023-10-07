@extends('layouts.master')
@section('page_title', 'Setup Progress')
@section('content')
@php
    function getColorForProgress($percentComplete) {
        // Ensure the percentage is within the valid range (0-100)
        $percentComplete = max(0, min(100, $percentComplete));

        // Define the color gradient
        $colors = [
            'red' => '#FF0000',
            'yellow' => '#FFFF00',
            'green' => '#00FF00',
        ];

        // Determine the color based on the percentage
        if ($percentComplete <= 50) {
            // Linear interpolation between red and yellow
            $redComponent = (100 - $percentComplete) * 2;
            $yellowComponent = $percentComplete * 2;
            $color = sprintf('#%02X%02X%02X', $redComponent, $yellowComponent, 0);
        } else {
            // Linear interpolation between yellow and green
            $yellowComponent = (100 - $percentComplete) * 2;
            $greenComponent = $percentComplete * 2;
            $color = sprintf('#%02X%02X%02X', 0, $yellowComponent, $greenComponent);
        }

        return $color;
    }
    $data = $errorObject;
    $totalPossibleError = count($data);
    $completedItems = array_filter($data,function($item){
        return $item['completed']== true;
    });
    $totalCompleted = count($completedItems);
    $percentComplete = ( $totalCompleted / $totalPossibleError)*100;
    $color = getColorForProgress($percentComplete);
@endphp
<style>
    .progress-color{
        background-color: {{ $color }};
    }
</style>


<div class="md:p-4 space-y-5">
    <div class="title flex items-center max-md:flex-col gap-3">
        <div class="w-full">
            <h1 class="text-green-500 m-0">Setup Progress</h1>
            <span>You're on  your way!</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
            <div class="progress-color text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: {{ $percentComplete }}%"> {{ round($percentComplete,2) }}%</div>
        </div>
    </div>
    <div class="border rounded-xl">
        <ul>
            @foreach ( $errorObject as $key => $value)
                <li class="border-b last:border-b-0 flex items-center gap-2 justify-between p-3">
                    <div class="status flex items-center justify-center ">
                        @if($value['completed'])
                        <span class="text-green-400 text-2xl flex"><i class="fi fi-br-check flex"></i></span>
                        @else
                        <span class="text-red-400 text-2xl flex"><i class="fi fi-sr-times-hexagon flex"></i></span>
                        @endif
                    </div>
                    <div class="name mr-auto font-medium text-2xl px-3 {{ $value['completed']?'text-green-600':'text-red-600' }}"> {{ $key }}</div>
                    <div class="message text-center max-md:hidden"> {{ $value['message'] }} </div>
                    @php
                    $route = $value['route'];
                    @endphp
                    <div class="action">
                        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown-{{ $key }}" class="text-white bg-slate-700 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-full text-sm px-2 py-2 text-center inline-flex items-center dark:bg-slate-600 dark:hover:bg-slate-700 dark:focus:ring-slate-800" type="button">
                            <i class="fi fi-rr-arrow-right flex text-xl"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdown-{{$key}}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                              <li>
                                <a href="{{route($value['route'][0])}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">go To Page</a>
                              </li>
                            </ul>
                        </div>

                    </div>
                </li>
            @endforeach

        </ul>
    </div>
</div>

@endsection
