@extends('layouts.app')

@section('content')

<div class="row mt-7">

    <div class="col-12 mb-2 mb-sm-5 main-title">
        <h1 class="marvel">LOGIN</h1>
    </div>

    <div class="col-12 mb-5 text-center">
        <form class="form-horizontal" method="POST" action="/login">
            {{ csrf_field() }}

            <div class="form-group col-12 col-xl-6 offset-xl-3 {{ $errors->has('email') ? 'has-error' : '' }}">
                <!-- <label for="email">Email address:</label> -->
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address" required autofocus>
            </div>

            <div class="form-group col-12 col-xl-6 offset-xl-3 {{ $errors->has('password') ? 'has-error' : '' }}">
                <!-- <label for="password">Password:</label> -->
                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="artshop-button mt-5 mb-3">
                    Login
                </button>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>

                <a class="artshop-link" href="/password/reset">
                    Forgot Your Password?
                </a>
            </div>
            
        </form>
    </div>

</div>

@endsection
