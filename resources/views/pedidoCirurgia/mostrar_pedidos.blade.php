@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ URL::to('/') }}/css/select2.min.css">
<link rel="stylesheet" href="{{ URL::to('/') }}/css/jquery.dataTables.min.css"> 
<script src="{{ URL::to('/') }}/js/select2.full.min.js"></script>
<script src="{{ URL::to('/') }}/js/jquery.dataTables.min.js"></script>

<style type="text/css">
table{
    
}
th, td {
    text-align: center;
}
</style>

@endsection
@section('content')

<div class='container'>
    <div class="row" style="text-align: center;">
        <div class="col-md-12">
            <h2 style="color: #364177;">Pesquisar Cirurgias (Pendentes)</h2>
        </div>
    </div>

    <div class='row' style=''>
        <form class="form-horizontal" id="pesquisaForm" name="pesquisaForm" method="post" style="padding-top: 2%;" action=""> 
        {{ csrf_field() }}
        <input type="hidden" name="cod_paciente" id="cod_paciente" value="">
            <div class='col-md-8 col-md-offset-2 panel panel-default' id='pesquisa' style="text-align: center; padding: 2%;">
                <div class='row'>
                <label class="" for="cod_pedido" id='lbl_cod_pedido'>Código do Pedido: </label>
                <input type="text" name="cod_pedido" id='cod_pedido'>
                </div>

                <div class='row' style="padding: 2%;">
                <label class="" for="prontuario" id="lbl_prontuario" style="padding-left: 4%;">Prontuário/Nome: </label>
                <select name="prontuario" id='prontuario' style="width: 70%;"></select>
                </div>
                 
                <button type="button" class="btn btn-default" id='limpar' style="margin-left: 4%;" onclick=""> Limpar</button>
                <button type="submit" class="btn btn-primary" id='submitform' style="margin-left: 4%;" onclick=""> Pesquisar</button>
            </div>
        </form>
    </div>

    <div class="row" id='relatorio' style="padding-top: 2%;">
        <div class="col-md-12 panel panel-default">
            <div class="panel-body">
            <div class='table-responsive'>
                <table class='table' id='table'>
                    <thead class="thead-light">
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Codigo do Pedido</th>
                            <th scope='col'>Situação</th>
                            <th scope='col'>Especialidade</th>
                            <th scope='col'>Local da Cirurgia</th>
                            <th scope='col'></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <div class='modal fade' id='modal-situacao' role='dialog'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type="button" class='close' data-dismiss='modal'>&times;</button>
                    <h4 class='modal-title' style="color: #364177;">Alterar Situação do Pedido</h4>
                </div>

                <div class='modal-body'>
                    <form class="form-horizontal" role="form" id="alterar-situacao" name="alterar-situacao" method="post" action="" method="post">
                        <!-- CRIA TOKEN CSRF PARA PROTEÇÃO -->
                        {{ csrf_field() }}

                        <div class="row" style="margin-top: 3%;">
                            <div class='col-xs-6' style="">
                                <label class="label_fonte " for="situacao_atual" id="lbl_situacao" style="">Situação Atual:</label>
                                <div> 
                                    <h5 name='situacao_atual' id="situacao_atual"></h5>
                                </div>
                            </div>

                            <div class='col-xs-6' style="">
                                <label class="label_fonte " for="situacao" id="lbl_situacao" style="">Nova Situação:</label>
                                <div>  
                                    <select name='situacao' id='situacao' style="width: 100%;">
                                        @foreach($situacoes as $item)
                                            <option value="{{ $item['cod_situacao'] }}">{{ $item['dsc_situacao'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row' style="margin-top: 3%;">
                            <div class='col-xs-12' style="">
                                <label class='label_fonte' for='motivo_alteracao' id='lbl_motivo_alteracao'>Motivo da alteração</label>
                                <textarea style="max-width: 100%; resize: none;" rows="4" name="motivo_alteracao" cols="80" id="motivo_alteracao">
                                </textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div class='col-xs-6'>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                        <div class='col-xs-6'>
                            <input type="submit" class="btn btn-primary" name="submit" value='Confirmar Alteração'/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
	$(document).ajaxStart(function () {
        $('#loadModal').modal('show');
    }).ajaxStop(function () {
        $('#loadModal').modal('hide');
    });

    var cod_situacao;
    var index_ped_datatable;

    $('#relatorio').hide();
    $('#table').DataTable();

    $('#situacao').select2({
        placeholder: "Selecione uma nova situação para o pedido"
    });

	// CONFIGURANDO PESQUISA DO PRONTUÁRIO VIA AJAX
    $("#prontuario").select2({
        placeholder: "Entre com o número do prontuário ou nome do paciente",
        minimumInputLength: 1,
        type: 'GET',
        ajax: {
            url: '{{ route('json_pacientes') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.id.toString().concat(" - ",item.text),
                            id: item.id
                        }
                    })
                };
            },
            cache: true,
        }
    });

    $('#prontuario').on('change', function(){ 

    	var token = $('input[name=_token]');
    	var prontuario = document.getElementById('prontuario').value;
    	var url = "{{ URL::action('PacienteController@getCodPaciente', ['id' => '__id__']) }}";

    	url = url.replace("__id__", prontuario);

		$.ajax({
		    url: url,
		    dataType:'json',
		    type: 'GET',
		    delay: 10000,
		    headers: {
    			'X-CSRF-TOKEN': token.val()
			},
		    success: function(data){
		    		$('#cod_paciente').val(data[0].cod_paciente);
			},
			error: function (jqXHR, exception) {
		        var msg = 'erro';
		        if (jqXHR.status === 0) {
		            msg = 'Not connect.\n Verify Network.';
		        } else if (jqXHR.status == 404) {
		            msg = 'Requested page not found. [404]';
		        } else if (jqXHR.status == 500) {
		            msg = 'Internal Server Error [500].';
		        } else if (exception === 'parsererror') {
		            msg = 'Requested JSON parse failed.';
		        } else if (exception === 'timeout') {
		            msg = 'Time out error.';
		        } else if (exception === 'abort') {
		            msg = 'Ajax request aborted.';
		        } else {
		            msg = 'Uncaught Error.\n' + jqXHR.responseText;
		        }
		        console.log(msg);
		    }
		});
	});

    // FUNCIONALIDADE DE LIMPAR DADOS INSERIDOS
    $('#limpar').on('click', function(e){
        $('#table tbody').empty();
        $('#cod_pedido').val('');
        $('#relatorio').hide();

        document.getElementById("lbl_prontuario").style.color = "gray";
        document.getElementById("lbl_cod_pedido").style.color = "gray";
        $('#prontuario').html('').select2({
            placeholder: "Entre com o número do prontuário ou nome do paciente",
            minimumInputLength: 1,
            type: 'GET',
            ajax: {
                url: '{{ route('json_pacientes') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.id.toString().concat(" - ",item.text),
                                id: item.id
                            }
                        })
                    };
                },
                cache: true,
            }
        });
    });

    // Variable to hold request
    var request;

    // SUBMIT
    $("#pesquisaForm").submit(function(event){
        $('#relatorio').show();
        $('#table').DataTable().destroy();
        $('#table tbody').empty();

    	// Prevent default posting of form - put here to work in case of errors
        event.preventDefault();

        // Abort any pending request
        if (request) {
            request.abort();
        }

        var token = $('input[name=_token]');
        var prontuario = $('#prontuario');
        var cod_paciente = document.getElementById('cod_paciente').value;
        var cod_pedido = document.getElementById('cod_pedido').value;

        if(prontuario.val() != null && cod_pedido != "")
        {
            document.getElementById("lbl_prontuario").style.color = "red";
            document.getElementById("lbl_cod_pedido").style.color = "red";
            document.getElementById("lbl_cod_pedido").focus();
            alert("Preencha apenas um dos dois campos (codigo pedido ou prontuário)!")
            return false;
        }
        else if(prontuario.val() == null && cod_pedido != "")
        {
            window.location = '{{URL::to('/')}}/pedidos/'+cod_pedido;
            return;
        }
        else
        {
            $('#relatorio').show();
            
            var url = "{{ URL::action('PedidoController@getPedidosByCodPaciente', ['id' => '__id__']) }}";
            
            url = url.replace("__id__", cod_paciente);

            // setup some local variables
            var $form = $(this);

            // Let's select and cache all the fields
            var $inputs = $form.find("input, select, button, textarea");

            // Serialize the data in the form
            var serializedData = $form.serialize();

            // Let's disable the inputs for the duration of the Ajax request.
            // Note: we disable elements AFTER the form data has been serialized.
            // Disabled form elements will not be serialized.
            $inputs.prop("disabled", true);

            // Fire off the request to /form.php
            request = $.ajax({
		      url: url,
    		    dataType:'json',
    		    type: 'GET',
    		    delay: 10000,
    		    headers: {
        			'X-CSRF-TOKEN': token.val()
    			},
    		    success: function(data){
    		    		$.each(data, function (index, value) {
                            if(this.cod_situacao == 1){
                                aux = " style='color: orange'>"+this.dsc_situacao;
                            }

                            else if(this.cod_situacao == 2){
                                aux = " style='color: green'>"+this.dsc_situacao;
                            }

                            else if(this.cod_situacao == 3 || this.cod_situacao == 4){
                                aux = " style='color: blue'>"+this.dsc_situacao;
                            }

                            else if(this.cod_situacao == 5){
                                aux = " style='color: black'>"+this.dsc_situacao;
                            }

                            else if(this.cod_situacao == 6){
                                aux = " style='color: red'>"+this.dsc_situacao;
                            }

                            else if(this.cod_situacao == 7){
                                aux = " style='color: yellow'>"+this.dsc_situacao;
                            }
                            
                            else if(this.cod_situacao == 8){
                                aux = " style='color: purple'>"+this.dsc_situacao;
                            }
                            else{
                                aux = " style=''>"+this.dsc_situacao;
                            }

                            tmp = "<tr><td>"+index+"</td> <td>"+this.cod_pedido+"</td> <td"+aux+"</td> <td>"+this.dsc_especialidade+"</td> <td>"+this.dsc_centro_cirurgico+"</td> <td><a href='{{URL::to('/')}}/pedidos/"+this.cod_pedido + "'" + "<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a> <a name='situacao' data-toggle='modal' data-target='#modal-situacao' data-cod-situacao='" + this.cod_situacao + "' data-index='"+index+ "' <span class='glyphicon glyphicon-sort' aria-hidden='true'></span></a> </td></tr>";

                            /*
                            tmp = "<tr><td>"+index+"</td> <td>"+this.cod_pedido+"</td> <td"+aux+"</td> <td>"+this.dsc_especialidade+"</td> <td>"+this.dsc_centro_cirurgico+"</td> <td><a href='{{URL::to('/')}}/pedidos/"+this.cod_pedido + "'" + "<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a> <a href='{{URL::to('/')}}/pedidos/"+this.cod_pedido+"/destroy'" + "<span class='glyphicon glyphicon-sort' aria-hidden='true'></span></a> </td></tr>";
                            */
    		    			
                        $('table tbody').append(tmp);
    		    		});
    			},
    			error: function (jqXHR, exception) {
    		        var msg = 'erro';
    		        if (jqXHR.status === 0) {
    		            msg = 'Not connect.\n Verify Network.';
    		        } else if (jqXHR.status == 404) {
    		            msg = 'Requested page not found. [404]';
    		        } else if (jqXHR.status == 500) {
    		            msg = 'Internal Server Error [500].';
    		        } else if (exception === 'parsererror') {
    		            msg = 'Requested JSON parse failed.';
    		        } else if (exception === 'timeout') {
    		            msg = 'Time out error.';
    		        } else if (exception === 'abort') {
    		            msg = 'Ajax request aborted.';
    		        } else {
    		            msg = 'Uncaught Error.\n' + jqXHR.responseText;
    		        }
    		        console.log(msg);
    		    }
			});

            // Callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                $('#table').DataTable();
                $("a[name = 'situacao']").click(function(){
                    cod_situacao = this.getAttribute('data-cod-situacao');
                    index_ped_datatable = this.getAttribute('data-index');
                    situacao_atual = $('#table').DataTable().row(index_ped_datatable).data();
                    situacao_atual = situacao_atual[2];

                    $("#situacao_atual").html(situacao_atual);

                    if(cod_situacao == 1){
                        $("#situacao_atual").css('color', 'orange');
                    }

                    else if(cod_situacao == 2){
                        $("#situacao_atual").css('color', 'green');
                    }

                    else if(cod_situacao == 3 || cod_situacao == 4){
                        $("#situacao_atual").css('color', 'blue');
                    }

                    else if(cod_situacao == 5){
                        $("#situacao_atual").css('color', 'black');
                    }

                    else if(cod_situacao == 6){
                        $("#situacao_atual").css('color', 'red');
                    }

                    else if(cod_situacao == 7){
                        $("#situacao_atual").css('color', 'yellow');
                    }
                    
                    else if(cod_situacao == 8){
                        $("#situacao_atual").css('color', 'purple');
                    }

                    //console.log(situacao_atual);
                    
                    //console.log(cod_pedido);
                    //console.log(situacao_pedido);
                    //console.log( $('#table').DataTable().row(situacao_pedido).data() );
                });
            });

            // Callback handler that will be called on failure
            request.fail(function (jqXHR, textStatus, errorThrown){
                // Log the error to the console
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            });

            // Callback handler that will be called regardless
            // if the request failed or succeeded
            request.always(function () {
                // Reenable the inputs
                $inputs.prop("disabled", false);
            });
        }
    });
});
</script>
@endsection