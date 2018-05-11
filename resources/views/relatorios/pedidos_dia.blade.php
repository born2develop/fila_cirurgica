@extends('layouts.app')

@section('styles')
<style type="text/css">
table{

}
th {
    text-align: center;
}
</style>
@endsection

@section('content')

<div class="container">
    <div class="row" style="text-align: center;">
        <div class="col-md-12">
            <h2 style="color: #364177;">Relatório - Pedidos de Cirurgia Feitos Hoje</h2>
        </div>
    </div>

    <div class="row" style="padding-top: 2%;">
        <div class="col-md-12 panel panel-default">
            <div class="panel-body">
            <div class='table-responsive'>
            	<table class='table' id='table'>
            		<thead class="thead-light">
	            		<tr>
                            <th scope='col'>#</th>
	            			<th scope="col">Pedido</th>
	            			<th scope="col">Data</th>
	            			<th scope="col">Operação</th>
	            			<th scope="col">Local Cirurgia</th>
	            			<th scope="col">Prontuário</th>
	            			<th scope="col">Nome</th>
	            			<th scope="col">Mãe</th>
	            			<th scope="col">Nascimento</th>
	            			<th scope="col">Telefone</th>
	            		</tr>
            		</thead>

            		<tbody>
                        @foreach($pedidos as $pedido)
                        <tr>
                            <th scope='row'>{{$loop->index}}</th>
                            <td>{{$pedido['cod_pedido']}}</td>
                            <td>{{$pedido['dte_pedido']}}</td>
                            <td>{{$pedido['dsc_tipo_operacao']}}</td>
                            <td>{{$pedido['dsc_encaminhamento']}}</td>
                            <td>{{$pedido['num_prontuario']}}</td>
                            <td>{{$pedido['txt_nome']}}</td>
                            <td>{{$pedido['txt_nome_mae']}}</td>
                            <td>{{$pedido['dte_nascimento']}}</td>
                            <td>({{$pedido['num_ddd_telefone_atual']}}){{$pedido['num_telefone_atual']}}</td>
                        </tr> 
                        @endforeach
            		</tbody>
            	</table>
            </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
    $('#table').DataTable();
} );
</script>
@endsection