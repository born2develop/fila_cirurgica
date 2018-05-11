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
    <link rel="stylesheet" href="{{ URL::to('/') }}/bootstrap-3.3.7-dist/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/bootstrap-3.3.7-dist/bootstrap-theme.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ URL::to('/') }}/bootstrap-3.3.7-dist/bootstrap.min.js"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
    
    <!-- <script src="{{ URL::to('/') }}/jquery-ui-1.12.1/external/jquery/jquery.js"></script> -->
    <script src="{{ URL::to('/') }}/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="{{ URL::to('/') }}/js/datapicker.js"></script>

    @yield('styles')
</head>
<body>
    <div id="app" style="overflow: hidden;">
        <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0px; display: inline-block;">
            <div class="row">
                <div class="col-xs-6 col-sm-6" style=""> 
                    <a href="{{ url('/home') }}" class="">
                        <img src="{{ URL::to('/') }}/img/logo_hub.jpg" class="align-top logo">
                    </a>
                </div>

                <div class="col-xs-6 col-sm-6" style="padding-top: 1%;">
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())

                        @else
                            <li style="text-align: center;">
                                <h5 style="color: #364177;"><b>{{ Auth::user()->name }} </b>
                                </h5>
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
