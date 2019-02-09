@extends('layouts.app')

@section('content')
<div class="" id="login-view">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">Register</h3><br>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="first_name" >{{ __('First name') }}</label>

                            <div>
                                <input id="first_name" type="text" placeholder="Firstname" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="last_name" >{{ __('Last name') }}</label>

                            <div >
                                <input id="last_name" type="text" placeholder="Lastname" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="email">{{ __('E-Mail Address') }}</label>

                            <div >
                                <input id="email" type="email" placeholder="Email"class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="password" >{{ __('Password') }}</label>

                            <div >
                                <input id="password" type="password" placeholder="Your password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="password-confirm" >{{ __('Confirm Password') }}</label>

                            <div>
                                <input id="password-confirm" placeholder="Confirm your password" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit"  class="btn btn-block btn-lg btn-dark">
                                    {{ __('Register') }}
                                </button>
                                <br>
                                <p class="text-center">
                                    <a href="{{ url('/login') }}" >Sign In</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
