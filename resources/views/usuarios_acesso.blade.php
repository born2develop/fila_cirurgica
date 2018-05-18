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
        <div class="col-xs-12">
            <h2 style="color: #364177;">Permissão de Acesso de Usuários</h2>
        </div>
    </div>

    <div class="row" style="padding-top: 2%;">
        <div class="panel panel-default panel-body col-xs-8 col-xs-offset-2">
            <form method='POST' class='form-horizontal' id='pesquisaForm'>
                <div class="col-xs-9 col-xs-offset-3">
                	{{ csrf_field() }}
                    <div class='form-group'>
                        <label for="usuario" class="col-xs-3 control-label">Login do Usuário:</label>
                        <div class='col-xs-9' style="">
                            <input type="text" name="usuario" id="usuario" style="width: 30%;">
                        </div>
                    </div>

                    <div class="row" style="margin-top: 5%;">
                        <div class='col-xs-3' style="text-align: center;">
                            <input type="button" class="btn btn-default" onclick="window.location='{{ route("home") }}'" name="cancelar" value='Cancelar'/>
                        </div>

                        <div class='col-xs-3' style="text-align: center; display: inline-block;">
                            <button type="submit" class="btn btn-primary" onclick="" name="submit" id='pesquisaSubmit' value=''>Pesquisar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row" id='relatorio' style="">
        <div class="col-xs-12 panel panel-default">
            <div class="panel-body">
            <div class='table-responsive'>
                <table class='table' id='table'>
                    <thead class="thead-light">
                        <tr>
                            <th scope='col'>Nome</th>
                            <th scope="col">Login</th>
                            <th scope='col'>Permissão</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <div class='modal fade' id='modal-permissao' role='dialog'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type="button" class='close' data-dismiss='modal'>&times;</button>
                    <h4 class='modal-title' style="color: #364177;">Alterar Permissão de {{Auth::user()->name}}</h4>
                </div>

                <div class='modal-body'>
                    <form class="form-horizontal" role="form" id="alterar-permissao" name="alterar-permissao" method="post" action="" method="post">
                        <!-- CRIA TOKEN CSRF PARA PROTEÇÃO -->
                        {{ csrf_field() }}

                        <div class="row" style="">
                            <div class='col-xs-6 col-xs-offset-3' style="">
                                <label class="label_fonte " for="permission" id="lbl_permission" style="">Conceder Permissão:</label>
                                <div>  
                                    <select name='permission' id='permission' style="">
                                        @foreach($permissoes as $item)
                                            <option value="{{ $item['cod_permission'] }}">{{ $item['dsc_permission'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <div class="row" style="text-align: center;">
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
        document.getElementById("block_loading_page").disabled = true;
        }).ajaxStop(function () {
        $('#loadModal').modal('hide');
        document.getElementById("block_loading_page").disabled = false;
    });

    $('#relatorio').hide();
    
    // Variable to hold request
    var request;
    var token = $('input[name=_token]');
    // Bind to the submit event of our form
    $("#pesquisaSubmit").on('click', function(e){
        $("#pesquisaForm").submit(function(event){
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
                url: "{{route('json_usuarios_ad')}}",
                dataType:'json',
                type: 'POST',
                delay: 20,
                data: serializedData,
                headers: {
                    'X-CSRF-TOKEN': token.val()
                },
                success: function(data){
                        tmp = "<tr><td>"+data[0].name+"</td> <td>"+data[0].username+ "</td> <td>";
                        if(data[0].cod_permission == null)
                            tmp += 'SEM ACESSO';
                        else
                            tmp += data[0].cod_permission;
                        tmp += "<a name='permissao' data-toggle='modal' data-target='#modal-permissao' data-user-id='"+ data[0].id + "'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>";

                        tmp += "</td></tr>";
                        $('table tbody').append(tmp);
                        
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