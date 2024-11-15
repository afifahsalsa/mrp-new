@extends('layouts.auth')
@section('content')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo">
                            <img src="{{ asset('purple-free/src/assets/images/logo.svg') }}">
                        </div>
                        <h4 class="font-weight-bold text-center">Welcome to MRP!</h4>
                        <h6 class="font-weight-light text-center">Sign in to your account.</h6>
                        <form class="pt-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    id="exampleInputEmail1" name="email" placeholder="Input your email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="current-password" id="exampleInputPassword1"
                                    placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                {{-- <a class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"
                                href="{{ route('dashboard') }}" type="submit">SIGN IN</a> --}}
                                <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"
                                    href="{{ route('dashboard') }}" type="submit">
                                    SIGN IN
                                </button>
                            </div>
                            <div class="text-center mt-4 font-weight-light"> Don't have an account? <a
                                    href="{{ route('register') }}" class="text-primary">Register Now!</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
@endsection
