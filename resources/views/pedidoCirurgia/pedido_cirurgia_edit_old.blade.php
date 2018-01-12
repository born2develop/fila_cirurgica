@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/select2.min.css">
    
    <script src="{{ URL::to('/') }}/js/select2.full.min.js"></script>
    <script src="{{ URL::to('/') }}/js/main.js"></script>
    <script src="{{ URL::to('/') }}/js/validacao_cadastrar_paciente.js"></script>


	<link rel="stylesheet" href="{{ URL::to('/') }}/css/estilo.css"/>
@endsection


@section('content')
<div class="container">
	    <div class="panel panel-body" style="display:block; margin: 0 auto; outline: #ffffff solid 0px; background-color: transparent;">
	    	<div class="col-sm-2"> </div>
			<div class="col-sm-8">
				<img src="{{ URL::to("/") }}/img/Cadastrar_Paciente.png" class="logofila" >
			</div>
			<div class="col-sm-2"> </div>
		</div>


	<div class="panel panel-default" style="border: 0;">
		<div class="panel panel-group">
		<form class="form-horizontal" role="form" id="inserir_fila" name="inserir_fila" method="post" action=" {{ route('pedidos.store') }}" onsubmit="return ValidateForm();">
		
			<!-- CRIA TOKEN CSRF PARA PROTEÇÃO -->
			{{ csrf_field() }}

			<!-- DIV QUE DÁ INÍCIO A SEÇÃO PEDIDO -->
			<div class="panel panel-body" style="background-color:  #e6f2ff; padding-top: 0; padding-bottom: 0;">
			<fieldset id="permitir_edicao">
				<div class="row" style="">
				<!-- -->
				<div class="col-sm-3 principal" style="">
					<div class="row">
						<label class="label_fonte br" for="cod_pedido" id="lbl_cod_pedido" style="color: #364177;">Número do pedido: </label>
						<input class="pedidoPrincipal" type="text" name="cod_pedido" id="cod_pedido" style="width: 50%;" value="" readonly>

						<label class="label_fonte br" for="data_pedido" id="lbl_data_pedido" style="color: #364177;">Data: </label>
						@if(isset($data_pedido))
						<input class="" type="date" name='data_pedido' id="data_pedido" value="{{$data_atual}}" style="text-align: center;" readonly>
						@else
						<input class="" type="date" name='data_pedido' id="data_pedido" value="{{$data_atual}}" style="text-align: center;">
						@endif
					</div>
				</div>

				<!-- -->
				<div class="col-sm-4 tipo_cirurgia" style="padding-left: 15%;">
					<label class="label_fonte" class="" for="operacao" id="lbl_operacao">Tipo de operação:</label>
					<ul>
						@foreach($tipo_operacao as $item)
					    	<li>
					    		<input type="radio" name="operacao" id="operacao{{ $item['cod_tipo_operacao']}}" value="{{ $item['cod_tipo_operacao']}}">
					    		<span class="h4">{{ $item['dsc_tipo_operacao'] }} </span>
					    	</li>
					    @endforeach
					</ul>
				</div>

				<!-- -->
				<div class="col-sm-5 tipo_cirurgia">
					<label class="label_fonte" class="" for="tipo_cirurgia" id="lbl_tipo_cirurgia">Tipo de cirurgia:</label>
					<ul>
						@foreach($centro_cirurgico as $item)
						    <li class="checkbox">
							    <input type="radio" name="tipo_cirurgia" id="tipo_cirurgia{{ $item['cod_tipo_centro_cirurgico']}}" value="{{ $item['cod_tipo_centro_cirurgico']}}">
							    <span class="h4">{{ $item['dsc_tipo_centro_cirurgico'] }} </span>
						    </li>
						@endforeach
					</ul>
				</div>
				</div>
			</fieldset>
			</div>

			<!-- DIV QUE DÁ INÍCIO A SEÇÃO PACIENTE -->
			<div class="panel panel-body" style="background-color:  #e6f2ff;">
			<fieldset id="permitir_edicao2">
				<div class="row rp3">
					<div class="col-sm-9">
						<!-- Dados com base no AGHU - IMPORTAR!! -->
							<label class="label_fonte" for="lbl_prontuario" id="lbl_prontuario" style="display: inline-block;">Prontuário e Nome: </label>
								<select class="prontuario" name="prontuario" id="prontuario" style="width: 74%;">
									<optgroup label class="Num Prontuário do Paciente">
									</optgroup>
								</select>
					</div>
					
					<input type="hidden" name="cod_paciente" id="123" value="">
					<input type="hidden" name="cod_paciente" id="cod_paciente" value="">

					<div class="col-sm-3">
						<label class="label_fonte offset-top" for="data_nascimento" id="lbl_data_nascimento">Nascimento: </label>
						<input type="date" class="disabled" name="data_nascimento" id="data_nascimento" readonly>
					</div>
					<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_prontuario">*Se não existir o prontuário, cadastrar primeiro no AGHU. </label>
				</div>

				<div class="row rp1">
					<div class="col-sm-1" id="divsexo">
				    	<label class="label_fonte" for="sexo" id="lbl_sexo">Sexo:<br/></label>
				    </div>

					<div class="col-sm-2">
						<input class="disabled" type="text" id="sexo" style="width: 100%;" readonly>
						<!--<ul class="font" style="background-color: #e6f2ff;">
					    	<li ><input type="radio" name="sexo" id="sexo" value="masculino"><span class="h4">Masculino</span></li>
					    	<li><input type="radio" class="rad-pad-l" id="sexo" name="sexo" value="feminino"><span class="h4">Feminino</span></li>
						</ul>-->
					</div>
					

					<div class="col-sm-6" style="white-space:nowrap">
						<label style="display: inline-block;" class="label_fonte" for="nome_mae" id="lbl_nome_mae">Nome da Mãe: </label>
						<input class="disabled" type="text" id="nome_mae" style="width: 68%;" readonly>
					</div>

					<div class="col-sm-3" style="white-space:nowrap">
					    <!-- -->
					    <label class="label_fonte" for="sus_id" id="lbl_sus_id">Nº SUS: </label>
					    <input type="text" class="disabled" name="sus_id" id="sus_id" style="width: 67%;" readonly> <br/>
					</div>
				</div>
					<!-- -->
					

			        <!-- PARA VINCULAR MAIS DADOS AO PACIENTE, SUGESTÃO FUTURA
			        <label class="label_fonte" for="cpf" id="lbl_cpf">CPF: </label>
			        <input type="text" id="cpf"> <br//>
					

			        <label class="label_fonte" for="rg" id="lbl_rg">RG: </label>
			        <input type="text" id="rg"> <br//>

			        <label class="label_fonte" for ="rg_orgao" id="lbl_rg_orgao">Órgão Emissor: </label>
			        <input type="text" id="rg_orgao"> <br//>

			        <label class="label_fonte" for="rg_uf" id="lbl_rg_uf">UF: </label>
			        <input type="text" id="rg_uf"> <br//>

			        <label class="label_fonte" for="rg_emissao" id="lbl_rg_emissao">Data de Emissão: </label>
			        <input type="text" id="rg_emissao"> <br//>
					
			        <label class="label_fonte" for="email" id="lbl_email">E-mail: </label>
			        <input type="text" id="email"> <br//>
			    -->

			    <!-- CASO VENHA ADICIONAR A IDADE (DESNECESSÁRIO)
				    <label class="label_fonte" for="idade" id="lbl_idade">Idade: </label>
				    <input type="text" id="idade"> <br//>
				-->
				    <!-- -->
			    <!-- -->

			    <div class="row rp1" style="margin-left: 0%;">
			    	<div class="col-sm-1" style="">
			    		<div class="row">
			    			<label class="label_fonte control-label" for="telefone_residencial">Contato:</label>
			    		</div>
			    		<div class="row">
			    			<label class="label_fonte control-label" for="telefone_recado">Recado:</label>
			    		</div>
					</div>
				    <div class="col-sm-2" style="" >
				    	<div class="row phone-list">
							<div class="input-group phone-input">
								<input type="text" name="telefone_residencial" id="telefone_residencial" class="disabled" readonly style="width: 90%;" />
							</div>
							<div class="input-group phone-input">
								<input type="text" name="telefone_recado" id="telefone_recado" class="disabled" readonly style="width: 90%;" />
							</div>
						</div>
					</div>

					<!-- Criar própria fonte de dados -->
				    <div class="col-sm-5">
					    <label class="label_fonte" for="especialidade" id="lbl_especialidade">Clínica: </label>
					    <select name="especialidade" id="especialidade">
					    	<option disabled selected value=""></option>
					    	@foreach($especialidades as $item)
						    	<option value="{{ $item['cod_especialidade'] }}">{{ $item['dsc_especialidade'] }}
						    	</option>
					    	@endforeach
					    </select>
				    </div>

		    		<div class="col-sm-3">
					    <!-- Valor único: SUS-->
					    <label class="label_fonte" for="convenio" id="lbl_convenio">Convenio: </label>
					    <select name="convenio" id="convenio">
					    	<option disabled selected value="-1"></option>
					    	@foreach($convenios as $item)
					    		<option value="{{ $item['cod_convenio'] }}"> {{ $item['dsc_convenio'] }} </option>
					    	@endforeach
					    </select>
		    		</div>

				</div>	

			    <div class="row rp1">
					<div class="col-sm-1" id="lbl_cid">
						<label class="label_fonte" for="cid" id="lbl_cid">CID: </label>
					</div>
		    		<div class="col-sm-10" style="white-space:nowrap">
		    			<div class="row">
			    			<div class="cid-list">
			    				<div class="col-sm-10 input-group cid-input">
			    					<!-- Dados com base no AGHU - IMPORTAR!! -->
			    					<select name="cid1" id="cid1" class="cid form-control" style="width: 93%;"></select>
			    					<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_cid" style="display: block;">*Se não existir o CID, cadastrar primeiro no AGHU. </label>
			    				</div>
							</div>
						</div>
						<button type="button" class="btn btn-success btn-sm btn-add-cid" id="btn-add-cid" style="height: 10%;"><span class="glyphicon glyphicon-plus"></span> Adicionar outro CID</button>
		    		</div>
		    	</div>
		    </fieldset>
			</div>

			<div class="panel panel-body" style="background-color:  #e6f2ff;">
				<fieldset id="permitir_edicao3">
		    	<div class="row rp1">
		    		<div class="col-sm-3">
		    			<!-- -->
					    <label class="label_fonte" for="sintomatologia" id="lbl_sintomatologia">Sintomatologia:</label>
					    <ul>
					    	@foreach($sintomatologia as $item)
						    	@if($item['cod_sintomatologia'] === 1)
							    	<li>
							    		<input type="radio" name="sintomatologia" id="sintomatologia{{ $item['cod_sintomatologia']}}" value="{{ $item['cod_sintomatologia']}}" checked="checked">
							    		<span class="h4">{{ $item['dsc_sintomatologia'] }}</span>
							    	</li>
						    	@else
							    	<li>
							    		<input type="radio" name="sintomatologia" id="sintomatologia{{ $item['cod_sintomatologia']}}" value="{{ $item['cod_sintomatologia']}}">
							    		<span class="h4">{{ $item['dsc_sintomatologia'] }} </span>
							    	</li>
						    	@endif
					    	@endforeach
					    </ul>	
		    		</div>

		    		<div class="col-sm-3">
					    <!-- -->
					    <label class="label_fonte" id="lbl_doenca_maligna">Doença Maligna: </label>
					    <ul>
						    @foreach($d_malignas as $item)
						    	@if($item['cod_doenca_maligna'] === 1)
							    	<li>
							    		<input type="radio" name="doenca_maligna" id="doenca_maligna{{ $item['cod_doenca_maligna']}}" value="{{ $item['cod_doenca_maligna']}}" checked="checked">
							    		<span class="h4">{{ $item['dsc_doenca_maligna'] }}</span>
							    	</li>
						    	@else
							    	<li>
							    		<input type="radio" name="doenca_maligna" id="doenca_maligna{{ $item['cod_doenca_maligna']}}" value="{{ $item['cod_doenca_maligna']}}">
							    		<span class="h4">{{ $item['dsc_doenca_maligna'] }} </span>
							    	</li>
						    	@endif
					    	@endforeach
					    </ul>
		    		</div>

		    		<div class="col-sm-3">
		    			<!-- -->
					    <label class="label_fonte" id="lbl_doenca_associada">Doenças Associadas: </label>
					    <ul>
					    	@for ($i = 0; $i < 4; $i++)
						    <li>
							    @if($d_associadas[$i]['cod_doenca_associada'] === 1)
							    	<input type="checkbox" id="doenca_associada{{ $d_associadas[$i]['cod_doenca_associada'] }}" class='name' name="doenca_associada{{$d_associadas[$i]['cod_doenca_associada']}}" value="{{ $d_associadas[$i]['cod_doenca_associada'] }}" onchange="doenca_ass_checkbox()" checked> {{$d_associadas[$i]['dsc_doenca_associada']}}
							    @else
								    <input type="checkbox" id="doenca_associada{{ $d_associadas[$i]['cod_doenca_associada'] }}" class='name' name="doenca_associada{{$d_associadas[$i]['cod_doenca_associada']}}" onchange="doenca_ass_checkbox2()" value="{{ $d_associadas[$i]['cod_doenca_associada'] }}"> {{$d_associadas[$i]['dsc_doenca_associada']}}
							    @endif
							</li>
						    @endfor
					    </ul>
		    		</div>

		    		<div class="col-sm-3">
		    			<!-- -->
					    <label class="label_fonte" for="operacao_porte" id="lbl_operacao_porte">Porte da operaçao: </label>
					    <ul>
					    	@foreach($porte_operacao as $item)
							    <li><input type="radio" name="operacao_porte" id="operacao_porte{{ $item['cod_porte_operacao'] }}" value="{{ $item['cod_porte_operacao'] }}">{{ $item['dsc_porte_operacao'] }}<br/></li>
					    	@endforeach
					    </ul>
		    		</div>

		    	</div>
		    	<div class="row rp1">
		    		<div class="col-sm-12">
		    			<!-- RETIRAR O TEXTAREA  PARA PADRONIZAR OS DADOS!! LINKAR COM A CLINICA! -->
					    <label class="label_fonte" for="procedimento" id="lbl_procedimento">Procedimento proposto: </label>
					    <textarea rows="3" cols="180" id="procedimento">
					    	SERÁ ALTERADO EM FUTURAS ATUALIZAÇÕES.
						</textarea>
		    		</div>
		    	</div>

		    	<!--  -->
		    	<div class="row rp1">
		    		<div class="col-sm-3">
		    			<label class="label_fonte" for="anestesia" id="lbl_anestesia">Tipo de Anestesia: </label>
		    			<ul>
		    				@foreach($anestesias as $item)
							    <li><input type="radio" name="anestesia" id="anestesia{{ $item['cod_anestesia'] }}" value="{{ $item['cod_anestesia'] }}">{{ $item['dsc_anestesia'] }}<br/></li>
					    	@endforeach
		    			</ul>
		    		</div>

		    		<!--  -->
		    		<div class="col-sm-3">
		    			<label class="label_fonte" for="exames_trans_op" id="lbl_exames_trans_op">Exames trans-operatório: </label>
		    			<ul>
		    				@foreach($exames_trans_op as $item)
		    					@if($item['cod_exames_trans_op'] === 3)
							    	<li>
							    		<input type="radio" name="exames_trans_op" id="exames_trans_op{{ $item['cod_exames_trans_op'] }}" value="{{ $item['cod_exames_trans_op'] }}" checked>{{ $item['dsc_exames_trans_op'] }}<br/>
							    	</li>
							    @else
							    	<li>
							    		<input type="radio" name="exames_trans_op" id="exames_trans_op{{ $item['cod_exames_trans_op'] }}" value="{{ $item['cod_exames_trans_op'] }}">{{ $item['dsc_exames_trans_op'] }}<br/>
							    	</li>
							    @endif
					    	@endforeach
		    			</ul>
		    		</div>

		    		<!--  -->
		    		<div class="col-sm-6">
		    			<label class="label_fonte br text-center" id="hemoderivados">Hemoderivados (Unid): </label>
		    			<div class="row">
		    				<div class="col-sm-2"></div>
		    				<div class="col-sm-5">
				    			<label class="label_sm_fnt br" for="conc_hemacias" id="lbl_conc_hemacias">Conc.Hemacias: </label>
								<input type="text" name="conc_hemacias" id="conc_hemacias" style="width:20%;" value='0'>
							</div>

							<div class="col-sm-5">
								<label class="label_sm_fnt br" for="plasma" id="lbl_plasma">Plasma: </label>
								<input type="text" name="plasma" id="plasma" style="width:20%;" value='0'>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-2"></div>
							<div class="col-sm-5">
								<label class="label_sm_fnt br" for="plaquetas" id="lbl_plaquetas">Plaquetas: </label>
								<input type="text" name="plaquetas" id="plaquetas" style="width:20%;" value='0'>
							</div>

							<div class="col-sm-5">
								<label class="label_sm_fnt br" for="crio_precipitado" id="lbl_crio_precipitado">Crio Precipitado: </label>
								<input type="text" name="crio_precipitado" id="crio_precipitado" style="width:20%;" value='0'>
							</div>
						</div>
					</div>
		    	</div>

				<!--  -->
				<div class="row rp1">
					<!-- -->
					<div class="col-sm-6 text-center">
						<label class="label_fonte" for="ortese_protese" id="lbl_ortese_protese">Material de órtese ou prótese: </label>
						<textarea rows="4" cols="90" id="ortese_protese">
							SERÁ ALTERADO EM FUTURAS ATUALIZAÇÕES.
						</textarea>
					</div>

					<!-- -->
					<div class="col-sm-6 text-center">
						<label class="label_fonte" for="inst_equip_especificos" id="inst_equip_especificos">Instrumentos e/ou equipamentos específicos: </label>
						<textarea rows="4" cols="90" id="inst_equip_especificos">
							SERÁ ALTERADO EM FUTURAS ATUALIZAÇÕES.vv
						</textarea>
					</div>
				</div>

				<!-- -->
				<div class="row rp1 text-center">
					<div class="col-sm-12">
						<label class="label_fonte br" for="obs" id="lbl_obs">Observações: </label>
						<textarea rows="3" name="observacao" cols="130" id="observacao">
						</textarea>
					</div>
				</div>
			</fieldset>
				<div class="row rp1">
					<div class="col-sm-12" style="background-color:  #e6f2ff;">
						<div style="float:left; margin-left:17%;">
							<input type="button" class="btn btn-default" onclick="window.location='{{ route("home") }}'" name="cancelar" id="cancelar" value='Cancelar'/>
						</div>

						<div style="float:right; margin-right:17%;">
								<input type="button" class="btn btn-primary" onclick="" id='formsubmit' name="formsubmit" value='Habilitar Edição' style="background-color: #20895E;" />
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<!-- <script src="js/bootstrap.min.js"></script>-->

	<!--  Autocomplete AJAX Script-->
<script type="text/javascript">

    $(document).ready(function() {

    	$("#cid1").select2({
            placeholder: "Entre com o(s) CID(s)",
            minimumInputLength: 2,
            type: 'GET',
            ajax: {
                url: '{{ route('json_cids') }}',
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


        $("#especialidade").select2();
        $("#convenio").select2();

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

    $('.prontuario').on('change', function(){ 
    	var token = $('input[name=_token]');

    	var prontuarioValue = document.getElementById('prontuario').value;
    	var url = "{{ URL::action('PacienteController@find', ['id' => '__id__']) }}";

    	url = url.replace("__id__", prontuarioValue);
    	console.log(url);
    	console.log("buscando dados do prontuário.");

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
		    		$('#data_nascimento').val(data[0].dte_nascimento);
		    		$('#sexo').val(data[0].txt_sexo.toUpperCase());
		    		$('#nome_mae').val(data[0].txt_nome_mae.toUpperCase());

		    		if(data[0].num_ddd_fone_residencial == null && data[0].num_fone_residencial == null)
		    			$('#telefone_residencial').val('Cadastre no AGHU');
		    		else
		    			$('#telefone_residencial').val(data[0].num_ddd_fone_residencial.toString().concat(' - ', data[0].num_fone_residencial.toString()));

		    		if(data[0].num_ddd_fone_recado == null && data[0].txt_fone_recadol == null)
		    			$('#telefone_recado').val('Cadastre no AGHU');
		    		else
		    			$('#telefone_recado').val(data[0].num_ddd_fone_recado.toString().concat(' - ', data[0].txt_fone_recado.toString()));


		    		if(data[0].num_cartao_saude == null || data[0].num_cartao_saude)
		    			$('#sus_id').val('Atualize no AGHU');
		    		else
		    			$('#sus_id').val(data[0].num_cartao_saude);
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
	
    $('.btn-add-cid').click(function(){


		var index = $('.cid-input').length + 1;

		$('.cid-list').append(''+
			'<div class="col-sm-10 input-group cid-input">'+
			'<select name="cid'+index+'" id="cid'+index+'" class="cid form-control" style="width: 95%;"></select>'+
			'<span>'+
			'<button style="width=98%;" class="btn btn-danger btn-remove-cid" type="button"><span class="glyphicon glyphicon-minus"></span></button>'+
			'</span>'+
			'<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_cid" style="display: block;">*Se não existir o CID, cadastrar primeiro no AGHU. </label>'+
			'</div>'
			);

		$('.cid').select2({
			placeholder: "Entre com o(s) CID(s)",
			minimumInputLength: 2,
			type: 'GET',
			ajax: {
				url: '{{ route('json_cids') }}',
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

	// PREENCHE OS DADOS CASO SE TRATE DE UMA BUSCA A UM PEDIDO CADASTRADO
	// Preencher campos SELECT2
    $('#prontuario')
      .empty()
      .append('<option selected value="{{ $cod_prontuario }}"> {{ $cod_prontuario }} - {{ $nome }}</option>');
    $('#prontuario').select2('data', {
      id: '{{ $cod_prontuario }}',
      label: '{{$nome}}'
    });
    $('#prontuario').trigger('change');
    $("#especialidade").val("{{$pedido['cod_especialidade']}}").trigger("change");
    $("#convenio").val("{{$pedido['cod_convenio']}}").trigger("change");

    // Preencher campos INPUT 

    // Preencher campos INPUT RADIO
    $('#operacao'.concat('{{$pedido['cod_tipo_operacao']}}')).prop("checked", true);
    $('#tipo_cirurgia'.concat('{{$pedido['cod_tipo_centro_cirurgico']}}')).prop("checked", true);
    $('#sintomatologia'.concat('{{$pedido['cod_sintomatologia']}}')).prop("checked", true);
    $('#doenca_maligna'.concat('{{$pedido['cod_doenca_maligna']}}')).prop("checked", true);
    $('#operacao_porte'.concat('{{$pedido['cod_porte_operacao']}}')).prop("checked", true);
    $('#anestesia'.concat('{{$pedido['cod_anestesia']}}')).prop("checked", true);
    $('#exames_trans_op'.concat('{{$pedido['cod_exames_trans_op']}}')).prop("checked", true);

    // Preencher campos INPUT TEXT
    $('#cod_pedido').val('{{$pedido['cod_pedido']}}');
    $('#conc_hemacias').val('{{$pedido['num_conc_hemacias']}}');
    $('#plasma').val('{{$pedido['num_plasma']}}');
    $('#plaquetas').val('{{$pedido['num_plaquetas']}}');
    $('#crio_precipitado').val('{{$pedido['num_crio_precipitado']}}');
    $('#observacao').val("{{$pedido['txt_observacoes']}}");


    @foreach($d_associadas as $item)
    	$('#doenca_associada{{$item['cod_doenca_associada']}}').prop('checked', false);
    @endforeach

    @foreach($rlc_doenca_associada as $item)
    	$('#doenca_associada{{$item['cod_doenca_associada']}}').prop('checked', true);
    @endforeach

	@for($i=1; $i < count($rlc_pedido_cid); $i++)
		document.getElementById("btn-add-cid").click();
	@endfor

    @for($i=1; $i <= count($rlc_pedido_cid); $i++)
    	$("#cid{{$i}}")
	      	.empty()
	      	.append('<option selected value="{{$rlc_pedido_cid[$i-1]['cod_cid']}}"> {{$rlc_pedido_cid[$i-1]['cod_cid']}} - {{$rlc_pedido_cid[$i-1]['dsc_cid']}}</option>');
    	$("#cid{{$i}}").select2('data', {
	      	id: '{{$rlc_pedido_cid[$i-1]['cod_cid']}}',
	      	label: '{{$rlc_pedido_cid[$i-1]['dsc_cid']}}'
    	});
    	$("#cid{{$i}}").trigger('change'); 	
    @endfor

	

	// VALIDA FORMULÁRIO PARA EDIÇÃO

	var edicao = false;
	$("#prontuario").prop('disabled', true);
	$('.cid').prop('disabled', true);
	$("#especialidade").prop('disabled', true);
    $("#convenio").prop('disabled', true);

    document.getElementById("permitir_edicao").disabled = true;
    document.getElementById("permitir_edicao2").disabled = true;
    document.getElementById("permitir_edicao3").disabled = true;

	$("#formsubmit").click(function() {
		if(edicao)
			if(ValidateForm())
			document.inserir_fila.submit();

		edicao = !edicao;

		if(edicao)
		{
			$("#permitir_edicao").prop('disabled', false);
			$("#permitir_edicao2").prop('disabled', false);
			$("#permitir_edicao3").prop('disabled', false);
			$("#formsubmit").css('background-color', '#2579a9');
			$("#formsubmit").prop('value', 'Alterar Dados');
			$("#prontuario").prop('disabled', false);
			$('.cid').prop('disabled', false);
			$("#especialidade").prop('disabled', false);
		    $("#convenio").prop('disabled', false);
		}
		else
		{

		}
	});
});
</script>
@endsection
