@extends('layouts.app')

@section('styles')
<style type="text/css">
.container{
    width: 100%;
    padding: 0;  
}

body{
    background: url({{ URL::to('/') }}/img/HUB_light.jpg) center no-repeat;
    background-repeat: ;
}


</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 col-md-offset-9" style="">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="row" style="">
                            <div class="col-md-12 {{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class=" control-label">Usuário</label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class=" control-label">Senha</label>
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Salvar senha
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Entrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
