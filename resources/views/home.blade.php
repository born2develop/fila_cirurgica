@extends('layouts.app')

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
                    <div class="links" style="display: inline;">
                        @if (Auth::user())
                            <div class="div-box-inline">
                                <a class="a" href="{{ route('pedidos.create') }}" style="padding-left: 8%;">
                                    <img src="{{ URL::to('/') }}/img/Icon_Cadastrar_Paciente_sb.png" class="menuoptions">
                                </a>
                                <h5 style="padding-left: 5%; color: #364177;">Cadastrar Paciente<br> (Solicitar Cirurgia)</h5>
                            </div>

                            <div class="div-box-inline">
                                <a class="a" data-toggle="modal" href="#myModal" style="padding-left: 8%;">
                                    <img src="{{ URL::to('/') }}/img/Icon_Search.png" class="menuoptions">
                                </a>
                                <h5 style="padding-left: 5%; color: #364177;">Pesquisar Pedido<br></h5>
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                    
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div class="modal-body">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Insira o número do pedido</h4>
                                            <form class="form-horizontal" id="setid" role="form" method="post" style="padding-top: 2%;" action=" {{ action('PedidoController@setId') }}"> 
                                                {{ csrf_field() }}
                                                <input type="text" id="id" name="id">
                                                <button type="submit" class="btn btn-primary" style="margin-left: 5%;" onclick="return carregando();"> Pesquisar</button>
                                            </form>
                                            <div class="loader" id="loader"></div>
                                        </div>
                                      </div>
                                    </div>
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
</div>
@endsection

@section('script')
    <script>
        function carregando()
        {
            if(document.getElementById('id').value == parseInt(document.getElementById('id').value, 10))
                return true;
            else
            {
                alert('Insira um código de pedido válido!');
                return false;
            }
        }
    </script>
@endsection