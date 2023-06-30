<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>JAP</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('fontawesome-all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('iofrm-style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('iofrm-theme11.css') }}">

        <style>
            .invalid-message{
                color: white;
                text-align: left;
                margin-bottom: 10px;
            }
        </style>

    </head>
    <body class="antialiased">
        <div class="form-body">
            <div class="row">
                <div class="form-holder">
                    <div class="form-content">
                        <div class="form-items">
                            <div class="website-logo-inside">
                                <a href="#">
                                    <div class="logo">
                                        <img class="logo-size" src="images/logo-light.svg" alt="">
                                    </div>
                                </a>
                            </div>
                            <h3>JAP</h3>
                            <p>Enter the following details to reset password.</p>
                            @if (session('error_status'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    {{ Session::get('error_status') }}
                                </div>
                            @endif
                            <form method="post" action="{{Request::getRequestUri()}}">
                                @csrf
                                <input class="form-control" type="text" name="email" placeholder="E-mail Address" required>
                                @error('email')
                                    <div class="invalid-message">{{ $message }}</div>
                                @enderror
                                <input class="form-control" type="password" name="password" placeholder="Password" required>
                                @error('password')
                                    <div class="invalid-message">{{ $message }}</div>
                                @enderror
                                <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                                @error('password_confirmation')
                                    <div class="invalid-message">{{ $message }}</div>
                                @enderror
                                <div class="form-button">
                                    <button id="submit" type="submit" class="ibtn">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
