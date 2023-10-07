<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kingsmeadschools') }}</title>

    @include('partials.login.inc_top')
</head>

<body class="" style="background-image: url('../../global_assets/images/backgrounds/v904-nunny-012_2.jpg');background-size: cover;display:block;">
@yield('content')
</body>

</html>
