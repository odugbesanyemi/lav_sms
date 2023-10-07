@extends('layouts.login_master')

@section('content')


    <!-- Main content -->
    <div class="" >
        <!-- image area -->
        <div class="w-9/12 h-full relative"  >
            <img class="w-full h-full object-cover absolute top-0 left-0" src="{{ asset('global_assets/images/backgrounds/kingsmead_front-page.jpg') }}" alt="">
        </div>
        <!-- Login card -->
        <div style="width: 400px !important" class=" right-0 h-screen my-auto flex justify-between flex-col bg-white/70 backdrop-blur-md p-5 absolute">
            <form method="post" action="{{ route('login') }}">
                @csrf
                <div class="z-1 rounded-0 border-0 space-y-3">
                    <div class="text-center mb-3">
                        <!-- <i class="icon-people icon-2x text-primary-600 border-primary-400 border-3 rounded-round p-3 mb-3 mt-1"></i> -->
                        <i class="bi bi-shield-lock text-blue-700" style="font-size:3rem;" ></i>
                        <h2 class="mb-0 font-bold text-slate-600">Login</h2>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger alert-styled-left alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <span class="font-weight-semibold">Oops!</span> {{ implode('<br>', $errors->all()) }}
                    </div>
                    @endif

                    <div class="relative mb-3">
                        <input placeholder="" required name="identity" type="text" value="{{ old('identity') }}" id="floating_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                        <label for="floating_outlined" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Login ID or Email</label>
                    </div>
                    <div class="relative mb-4">
                        <input placeholder="" required name="password" type="password" id="floating_outlined" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                        <label for="floating_outlined" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">{{ __('Password') }}</label>
                    </div>

                    <div class="form-group d-flex align-items-center mb-5">
                        <div class="form-check mb-0">
                            <label class="form-check-label space-x-2">
                                <input type="checkbox" name="remember" class="form-input-styled " {{ old('remember') ? 'checked' : '' }} data-fouc>
                                <span class="px-1 text-slate-500">Remember</span>
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}" class="ml-auto text-slate-800 ">Forgot password?</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="px-5 w-full bg-blue-700 text-white rounded focus:ring-4 focus:ring-blue-500 hover:bg-blue-800 font-medium text-lg flex justify-center space-x-3 py-2.5 ">Sign in <i class="bi bi-arrow-right text-xl ml-3"></i></button>
                    </div>
                </div>
            </form>
            <div class="text-slate-500">
                <div class="mx-auto flex-col flex justify-center">
                    <span class="navbar-text flex text-center mx-auto py-1">
                        &copy; {{ date('Y') }}. <a href="#" class="text-slate-500">{{ Qs::getSystemName() }}</a>
                    </span>
                    <div class="list-none d-flex align-items-center justify-content-center">
                        <a href="{{ route('privacy_policy') }}" class="navbar-nav-link py-1 text-slate-800" target="_blank"><i class="icon-lifebuoy mr-2"></i> Privacy Policy </a>
                        <a href="{{ route('terms_of_use') }}" class="navbar-nav-link  py-1 text-slate-800" target="_blank"><i class="icon-file-text2 mr-2"></i> Terms of Use </a>
                        {{--<li class="nav-item"><a href="#" class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i class="icon-phone mr-2"></i> Contact Us</span></a></li>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
