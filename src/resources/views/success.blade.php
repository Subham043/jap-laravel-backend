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

    </head>
    <body class="antialiased">
        <div class="form-body">
            <div class="row">
                <div class="form-holder">
                    <div class="form-content">
                        <div class="form-items">
                            <div class="website-logo-inside mb-0">
                                <a href="#">
                                    <div class="logo">
                                        <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                                        <lord-icon
                                            src="https://cdn.lordicon.com/lupuorrc.json"
                                            trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a"
                                            style="width:250px;height:180px">
                                        </lord-icon>
                                    </div>
                                </a>
                            </div>
                            <h3>JAP</h3>
                            @if (session('success_status'))
                                <p>{{ Session::get('success_status') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
