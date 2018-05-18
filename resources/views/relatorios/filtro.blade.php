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

<div class="container">
    <div class="row" style="text-align: center;">
        <div class="col-md-12">
            <h2 style="color: #364177;">MONTAR RELATÓRIO</h2>
        </div>
    </div>

    <div class="row" style="padding-top: 2%;">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default panel-body col-md-12">
                <form class='form-horizontal' method='POST' id='filtroForm' action="{{ action('RelatorioController@excel') }}">
                    {{ csrf_field(); }}
                    <div class='form-group'>
                        <label for="data_inicio" class="col-md-2 control-label">Data inicial:</label>
                        <div class='col-md-2' style="">
                            <input type="text" name="data_inicio" id="data_inicio">
                        </div>
                    </div>

                    <div class='form-group'>
                        <label for="data_fim" class="col-md-2 control-label">Data final:</label>
                        <div class='col-md-2' style="">
                            <input type="text" name="data_fim" id="data_fim">
                        </div>
                    </div>

                    <div class='form-group'>
                        <label for="especialidade" class="col-md-2 control-label">Clínica:</label>
                        <div class='col-md-10' style="">
                            <select name="especialidade" id="especialidade" style="width: 92%;">
                                <option selected value=""></option>
                                @foreach($especialidades as $item)
                                    <option value="{{ $item['cod_especialidade'] }}">{{ $item['dsc_especialidade'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class="col-md-2 control-label" for="lbl_cirurgiao" id="lbl_cirurgiao">Cirurgião: </label>
                        <div class='col-md-10' style="">
                            <select class="" name="cirurgiao" id="cirurgiao" style="width: 92%;">
                                 <option selected value="">Selecione</option>
                            </select>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class="col-md-2 control-label" for="lbl_procedimento" id="lbl_procedimento">Procedimento: </label>
                        <div class='col-md-10' style="">
                            <select class="" name="procedimento" id="procedimento" style="width: 92%;">
                                 <option selected value="">Selecione</option>
                            </select>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class="col-md-2 control-label" for="situacao" id="lbl_situacao" style="">Situação:</label>
                        <div class='col-md-10' style="">
                            <select name='situacao' id='situacao' style="width: 92%;">
                                <option selected value=""></option>
                                @foreach($situacoes as $item)
                                    <option value="{{ $item['cod_situacao'] }}">{{ $item['dsc_situacao'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-top: 3%; border-top: 1px solid #d3e0e9; padding-top: 2%;">
                        <div class='col-sx-6 col-md-3' style="text-align: center;">
                            <input type="button" class="btn btn-default" onclick="window.location='{{ route("home") }}'" name="cancelar" value='Cancelar'/>
                        </div>

                        <div class='col-sx-6 col-md-4' style="text-align: center;">
                            <button type="button" class="btn btn-default" id='limpar' style="margin-left: 4%;" onclick=""> Limpar</button>
                        </div>

                        <div class='col-sx-12 col-md-5' style="text-align: center; display: inline-block;">
                            <button type="submit" class="btn btn-primary" onclick="" name="submit" id='filtroSubmit' value=''>Visualizar Relatório</button>
                            <br/>
                            <input type="image" name='excel' id='excel' src="{{ URL::to('/') }}/img/icon_excel.png" style="width: 15%; margin-top: 3%;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row" id='relatorio' style="padding-top: 2%;">
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
    $(document).ajaxStart(function () {
        $('#loadModal').modal('show');
        document.getElementById("block_loading_page").disabled = true;
        }).ajaxStop(function () {
        $('#loadModal').modal('hide');
        document.getElementById("block_loading_page").disabled = false;
    });

    $('#relatorio').hide();
    $('#table').DataTable();
    $("#data_inicio").datepicker({});
    $("#data_fim").datepicker({});
    $("#procedimento").select2({
            placeholder: "Entre com o Procedimento",
            minimumInputLength: 2,
            type: 'GET',
            ajax: {
                url: '{{ route('json_procedimentos') }}',
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
                cache: true
            }
        });

    $("#especialidade").select2({
        placeholder: "Entre com o nome da clínica"
    });

    $("#situacao").select2({
        placeholder: "Entre com a situação do pedido"
    });

    $("#cirurgiao").select2({
        placeholder: "Entre com o nome do cirurgiao",
        minimumInputLength: 2,
        type: 'GET',
        ajax: {
            url: '{{ route('json_servidores') }}',
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
        cache: true
        }
    });

    // FUNCIONALIDADE DE LIMPAR DADOS INSERIDOS
    $('#limpar').on('click', function(e){
        $('#table tbody').empty();
        $('#relatorio').hide();
        $('#data_inicio').val('');
        $('#data_fim').val('');
        $('#cirurgiao').html('').select2({
            placeholder: "Entre com o nome do cirurgiao",
            minimumInputLength: 4,
            type: 'GET',
            ajax: {
                url: '{{ route('json_servidores') }}',
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
            cache: true
            }
        });


    });

    // Variable to hold request
    var request;
    var token = $('input[name=_token]');
    // Bind to the submit event of our form
    $("#filtroSubmit").on('click', function(e){
        $("#filtroForm").submit(function(event){
            $('#table').DataTable().destroy();
            $('#table tbody').empty();
            $('#relatorio').show();

            // Prevent default posting of form - put here to work in case of errors
            event.preventDefault();

            // Abort any pending request
            if (request) {
                request.abort();
            }
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
                url: "{{route('json_relatorios')}}",
                dataType:'json',
                type: 'POST',
                delay: 20,
                data: serializedData,
                headers: {
                    'X-CSRF-TOKEN': token.val()
                },
                success: function(data){
                        $.each(data, function (index, value) {
                            tmp = "<tr><td>"+index+"</td> <td>"+this.cod_pedido+"</td> <td>"+this.dte_pedido+"</td> <td>"+this.dsc_tipo_operacao+"</td> <td>"+ this.dsc_encaminhamento+"</td> <td>"+this.num_prontuario+"</td> <td>"+this.txt_nome+"</td> <td>"+this.txt_nome_mae+"</td> <td>"+this.dte_nascimento+"</td> <td>"+this.num_ddd_telefone_atual+this.num_telefone_atual+"</td></tr>";
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
                // Log a message to the console
                console.log("Con");
                $('#table').DataTable();
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

        });
    });
});
</script>
@endsection