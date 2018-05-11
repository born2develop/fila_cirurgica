@extends('layouts.app')

@section('styles')
<style type="text/css">
body{
    background: url({{ URL::to('/') }}/img/HUB_light.jpg) center no-repeat;
    background-repeat: ;
}
</style>
@endsection

@section('content')

<div class="container">
    <div class="row" style="text-align: center;">
        <div class="col-md-12">
            <h2 style="color: #364177;">Aplicativo para Gestão da Fila Cirúrgica</h2>
        </div>
    </div>

    <div class="row" style="padding-top: 2%;">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if (Auth::user())
                    <div class='linha' style="text-align: center;">
                        <div class="col-md-4 panel">
                            <a class="a" href="{{ route('pedidos.create') }}" style="padding-left: 8%;">
                                <img src="{{ URL::to('/') }}/img/Icon_Cadastrar_Paciente_sb.png" class="menuoptions">
                                <h5 style="padding-left: 5%; color: #364177;">Cadastrar Paciente<br> (Solicitar Cirurgia)</h5>
                            </a>
                        </div>

                        <div class="col-md-4 panel">
                            <a class="a" href="{{route('pedidos')}}" style="padding-left: 8%;">
                                <img src="{{ URL::to('/') }}/img/Icon_Search.png" class="menuoptions">
                                <h5 style="padding-left: 5%; color: #364177;">Pesquisar Pedido</h5>
                            </a>
                        </div>

                        <div class="col-md-4 panel">
                            <a class="a" href="{{route('relatorios.index')}}" style="padding-left: 8%;">
                                <img src="{{ URL::to('/') }}/img/icon_edit.png" class="menuoptions">
                                <h5 style="padding-left: 5%; color: #364177;">Relatórios</h5>
                            </a>
                                <!-- <img src="{{ URL::to('/') }}/img/Icon_Search.png" class="menuoptions"> -->
                        </div>
                    </div>
                    @else
                        Logue-se antes de acessar.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection