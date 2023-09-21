@extends('layouts.login_master')

@section('content')
    <div class="page-content d-flex align-items-center login-cover pb-2 pb-md-0 pt-md-0 pt-2">

        <!-- Main content -->
        <div class="text-center container  p-10 p-md-0 py-md-5">
            <!-- image area -->

            <!-- Content area -->
            <div class="d-flex flex-column flex-md-row w-md-75 w-100 gap-0 mx-auto overflow-hidden bg-white" >
                <!-- image area -->
                <div class="w-100 flex-fill col-md position-relative d-block p-0 " style="background-image:url('global_assets/images/backgrounds/kingsmead_front-page.jpg');min-height:fit-content;object-fit:contain;background-repeat: repeat;" >
                    <!-- <img src="/global_assets/images/backgrounds/kingsmead_front-page.jpg" class="position-absolute top-0 w-100 left-0 " style="height:;"  /> -->
                </div>
                <!-- Login card -->
                <form class="w-100 flex-fill col-md p-0 rounded-0" method="post" action="{{ route('login') }}">
                    @csrf
                    <div class="card mb-0 rounded-0 border-0">
                        <div class="card-body z-1 rounded-0 border-0">
                            <div class="text-center mb-3">
                                <!-- <i class="icon-people icon-2x text-primary-600 border-primary-400 border-3 rounded-round p-3 mb-3 mt-1"></i> -->
                                <i class="bi bi-shield-lock" style="font-size:3rem;" ></i>
                                <h5 class="mb-0">Login to your account</h5>
                                <span class="d-block text-muted">Your credentials</span>
                            </div>

                                @if ($errors->any())
                                <div class="alert alert-danger alert-styled-left alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <span class="font-weight-semibold">Oops!</span> {{ implode('<br>', $errors->all()) }}
                                </div>
                                @endif


                            <div class="form-group ">
                                <input type="text" class="form-control" name="identity" value="{{ old('identity') }}" placeholder="Login ID or Email">
                            </div>

                            <div class="form-group ">
                                <input required name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">

                            </div>

                            <div class="form-group d-flex align-items-center">
                                <div class="form-check mb-0">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="remember" class="form-input-styled" {{ old('remember') ? 'checked' : '' }} data-fouc>
                                        Remember
                                    </label>
                                </div>

                                <a href="{{ route('password.request') }}" class="ml-auto">Forgot password?</a>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Sign in <i class="bi bi-arrow-right"></i></button>
                            </div>

                           {{-- <div class="form-group">
                                <a href="#" class="btn btn-light btn-block"><i class="icon-home"></i><x-icon-camera/> Back to Home</a>
                            </div>--}}


                        </div>
                    </div>
                </form>

            </div>
            <div class="mt-5">
                <div class="mx-auto">
                    <span class="navbar-text text-white py-1">
                        &copy; {{ date('Y') }}. <a href="#" class="text-white">{{ Qs::getSystemName() }}</a>
                    </span>
                    <div class="list-none d-flex align-items-center justify-content-center">
                        <a href="{{ route('privacy_policy') }}" class="navbar-nav-link text-white py-1" target="_blank"><i class="icon-lifebuoy mr-2"></i> Privacy Policy </a>
                        <a href="{{ route('terms_of_use') }}" class="navbar-nav-link text-white py-1" target="_blank"><i class="icon-file-text2 mr-2"></i> Terms of Use </a>
                        {{--<li class="nav-item"><a href="#" class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i class="icon-phone mr-2"></i> Contact Us</span></a></li>--}}
                    </div>
                </div>
            </div>

        </div>

    </div>
    @endsection
