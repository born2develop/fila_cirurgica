<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- BOOTSTRAP -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FilaCirurgica') }}</title>

    <!-- Styles -->
    
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/estilo_app.css"/>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
    
    <!-- <script src="{{ URL::to('/') }}/jquery-ui-1.12.1/external/jquery/jquery.js"></script> -->
    <script src="{{ URL::to('/') }}/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="{{ URL::to('/') }}/js/datapicker.js"></script>

    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0px;">
            
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <!-- {{ config('app.name', 'FilaCirurgica') }} -->
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <a href="{{ url('/home') }}" style="text-align: center;">
                    <img src="{{ URL::to('/') }}/img/logo_hub.jpg" class="nav navbar-nav navbar-center logo">
                    </a>
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())

                        @else
                            <li>
                                    <h4 style="padding-top: 5%; color: #364177;"><b>{{ Auth::user()->name }} </b>
                                    </h4>
                                    <a href="{{ route('logout') }}" class="a" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <img src="{{ url::to('/') }}/img/icon_sair.png" class='icon'>
                                    </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                            </li>
                        @endif
                    </ul>
                </div>

        </nav>

        <div class="modal" id="loadModal" role="dialog">
            <div class="modal-dialog modal-sm modal-dialog-centered">

              <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">
                            <div class="loader" id="loader"></div>
                    </div>
                </div>
            </div>
        </div>
        <fieldset id='block_loading_page'>
        @yield('content')
        </fieldset>
    </div>
    @yield('script')
    <!-- Scripts 
    <script src="{{ asset('js/app.js') }}"></script>
    -->
</body>
</html>
