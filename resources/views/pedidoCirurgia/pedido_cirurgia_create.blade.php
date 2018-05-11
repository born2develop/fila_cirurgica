@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ URL::to('/') }}/css/select2.min.css">

<script src="{{ URL::to('/') }}/js/select2.full.min.js"></script>
<script src="{{ URL::to('/') }}/js/main.js"></script>
<script src="{{ URL::to('/') }}/js/validacao_cadastrar_paciente.js"></script>
<style type="text/css">
.principal{
	outline: 1px solid #364177;
}

.pedidoPrincipal{
	background-color:#e6f2ff;
    border: none;
    outline:none;
    transition:height 1s;
    -webkit-transition:height 1s;
    font-size: 150%;
    color: #364177;
    text-align: center;
}

.row{
	max-width: 100%;
}

.form-group{
	padding-left: 10px;
}
</style>
<link rel="stylesheet" href="{{ URL::to('/') }}/css/estilo.css"/>
@endsection


@section('content')

<div class="container">
	    <div class="panel panel-body" style="display:block; margin: 0 auto; outline: #ffffff solid 0px; background-color: transparent;">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
				<img src="{{ URL::to("/") }}/img/Cadastrar_Paciente.png" class="logofila" >
			</div>
		</div>

	<div class="panel panel-default" style="border: 0;">
		<div class="panel panel-group">
		<form class="form-horizontal" role="form" id="inserir_fila" name="inserir_fila" method="post" action=" {{ route('pedidos.store') }}" onsubmit="return ValidateForm();">
			<!-- CRIA TOKEN CSRF PARA PROTEÇÃO -->
			{{ csrf_field() }}

			<!-- DIV QUE DÁ INÍCIO A SEÇÃO PEDIDO -->
			<div class="panel panel-body" style="background-color:  #e6f2ff; padding-top: 0; padding-bottom: 0;">
				<div class="row" style="">
					<!-- -->
					<div class="col-xs-12 col-md-2 principal" style="">
						<div class='form-group' style="text-align: center;">
							<label class="label_fonte label-control" for="cod_pedido" id="lbl_cod_pedido" style="color: #364177;">Pedido: </label>
							<div class='col-xs-12' style="">
								<input class="form-control" type="text" name="cod_pedido" id="cod_pedido" value="" readonly>
							</div>
						</div>	

						<div class='form-group' style="text-align: center;">
							<label class="label_fonte label-control" for="data_pedido" id="lbl_data_pedido" style="color: #364177;">Data: </label>
							<div class='col-xs-12 col-md-12' style="">
								<input class="form-control" type="date" name='data_pedido' id="data_pedido" value="{{$data_atual}}" style="">
							</div>
						</div>
					</div>

					<!-- -->
					<div class="col-xs-6 col-md-3" style="">
						<div class="row">
							<div class="col-xs-12" style="">
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
						</div>

						<div class="row" style="margin-top: 3%;">
							<div class='col-xs-12' style="display: inline-block;">
								<label class="label_fonte " for="data_pedido" id="lbl_data_pedido" style="color: #364177;">Situação:</label>
								<h5 name='data_pedido' id="data_pedido" style="color: orange; font-weight: bold;">Aguardando avaliação de risco</h5>
							</div>
						</div>
					</div>

					<!-- -->
					<div class="col-xs-6 col-md-4">
						<label class="label_fonte" class="" for="tipo_cirurgia" id="lbl_tipo_cirurgia">Local da cirurgia:</label>
						<ul>
							@foreach($centro_cirurgico as $item)
							    <li class="checkbox">
								    <input type="radio" name="tipo_cirurgia" id="tipo_cirurgia{{ $item['cod_centro_cirurgico']}}" value="{{ $item['cod_centro_cirurgico']}}">
								    <span class="h4">{{ $item['dsc_centro_cirurgico'] }} </span>
							    </li>
							@endforeach
						</ul>
					</div>

					<!-- -->
					<div class="col-xs-8 col-md-3">
						<label class="label_fonte" class="" for="encaminhamento" id="lbl_encaminhamento">Encaminhamento:</label>
						<ul>
							@foreach($encaminhamento as $item)
							    <li class="checkbox">
								    <input type="radio" name="encaminhamento" id="encaminhamento{{ $item['cod_encaminhamento']}}" value="{{ $item['cod_encaminhamento']}}">
								    <span class="h4">{{ $item['dsc_encaminhamento'] }} </span>
							    </li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
			
			<!-- DIV QUE DÁ INÍCIO A SEÇÃO PACIENTE -->
			<div class="panel panel-body" style="background-color:  #e6f2ff;">
				<div class="row rp3">
					<div class='col-xs-12 col-md-8'>
						<div class='form-group'>
							<label class="label_fonte label-control" for="prontuario" id="lbl_prontuario" style="">Prontuário/Nome: </label>
							<select class="prontuario form-control" name="prontuario" id="prontuario" style="">
								<optgroup label class="Num Prontuário do Paciente">
								</optgroup>
							</select>
							<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_prontuario" style="display: block;">*Se não existir o prontuário, cadastrar primeiro no AGHU. </label>
						</div>
					</div>

					<input type="hidden" name="nome_paciente" id="nome_paciente" value="">
					<input type="hidden" name="cod_paciente" id="cod_paciente" value="">

					<div class="col-xs-12 col-md-4">
						<div class='form-group'>
							<label class="label_fonte label-control" for="data_nascimento" id="lbl_data_nascimento">Nascimento: </label>
							<input type="date" class="disabled" name="data_nascimento" id="data_nascimento" readonly>
						</div>
					</div>				
				</div>

				<div class="row rp1">
					<div class="col-xs-6 col-md-2">
						<div class='form-group' style="">
							<label class="label_fonte control-label" for="sexo" id="lbl_sexo" style="">Sexo:</label>
							<input class="disabled" type="text" id="sexo" style="max-width: 30%;" readonly style="">
						</div>
					</div>

					<div class="col-xs-6 col-md-4 col-md-push-6" style="white-space:nowrap">
					    <div class='form-group'>
						    <label class="label_fonte control-label" for="sus_id" id="lbl_sus_id">Nº SUS: </label>
						    <input type="text" class="disabled" name="sus_id" id="sus_id" style="width: 67%;" readonly> <br/>
					    </div>
					</div>

					<div class="col-xs-12 col-md-6 col-md-pull-4" style="">
						<div class='form-group'>
							<label style="" class="label_fonte control-label" for="nome_mae" id="lbl_nome_mae">Nome da Mãe: </label>
							<input class="disabled" type="text" id="nome_mae" style="width: 68%;" readonly>
						</div>
					</div>
				</div>

			    <div class="row rp1" style="margin-left: 0%;">
				    <div class="col-xs-6 col-md-3" style="" >
				    	<div class='form-group'>
							<div class="row col-md-3 input-group phone-input" style="white-space:nowrap">
								<label class="label_fonte control-label" for="telefone_residencial" id='lbl_telefone_residencial' style="">Contato:</label>
								<input type="text" name="telefone_residencial" id="telefone_residencial" class="disabled" readonly style="" />
							</div>
						
							<div class="row col-md-3 input-group phone-input" style="white-space:nowrap">
								<label class="label_fonte control-label" for="telefone_recado" id='lbl_telefone_recado'>Recado:</label>
								<input type="text" name="telefone_recado" id="telefone_recado" class="disabled" readonly style="" />
							</div>
						</div>
					</div>

				    <div class="col-xs-6 col-md-5">
				    	<div class='form-group' style="">
					    <label class="label_fonte control-label" for="especialidade" id="lbl_especialidade">Clínica: </label>
						    <select name="especialidade" id="especialidade">
						    	<option selected value=""></option>
						    	@foreach($especialidades as $item)
							    	<option value="{{ $item['cod_especialidade'] }}">{{ $item['dsc_especialidade'] }}
							    	</option>
						    	@endforeach
						    </select>
						</div>
				    </div>

		    		<div class="col-md-3 col-xs-6">
					    <!-- Valor único: SUS-->
					    <div class='form-group'>
					    <label class="label_fonte control-label" for="convenio" id="lbl_convenio">Convenio: </label>
						    <select name="convenio" id="convenio">
						    	<option selected value="-1">Selecione</option>
						    	@foreach($convenios as $item)
						    		@if($item['cod_convenio']=1)
						    			<option value="{{ $item['cod_convenio'] }}" selected> {{ $item['dsc_convenio'] }} </option>
						    		@else
						    			<option value="{{ $item['cod_convenio'] }}"> {{ $item['dsc_convenio'] }} </option>
						    		@endif
						    	@endforeach
						    </select>
						</div>
		    		</div>
				</div>

				<div class="row rp1">
					<div class="col-xs-12" >
						<div class='form-group'>
							<label class="label_fonte control-label" for="cirurgiao" id="lbl_cirurgiao" style="">Cirurgião responsável: </label>
							<select class="" name="cirurgiao" id="cirurgiao" style="">
								<optgroup label class="Num Prontuário do Paciente">
								</optgroup>
							</select>
							<label class="aviso_aghu control-label" class="aviso_aghu" id="lbl_aviso_cirurgiao" style="">*Se não existir o cirurgião, ele deverá ser cadastrado no AGHU. </label>
						</div>
					</div>
				</div>

		    	<div class="row rp1" style="max-width: 100%;">
					<div class="col-xs-2" id="lbl_cid" style="padding-left: 10px;">
						<label class="label_fonte" for="cid" id="lbl_cid" style="">CIDs relacionados: </label>
					</div>
		    		<div class="col-xs-10">
		    			<div class='form-group'>
			    			<div class="cid-list" style="max-width: 100%;">
			    				<div class="col-xs-11 input-group cid-input" style="max-width: 100%;">
			    					<select name="cid1" id="cid1" class="cid form-control" style=""></select>
			    					<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_cid" style="width: 95%;">*Se não existir o CID, cadastrar primeiro no AGHU. </label>
			    				</div>
							</div>
							<button type="button" class="btn btn-success btn-sm btn-add-cid" style="height: 10%;"><span class="glyphicon glyphicon-plus"></span> Adicionar outro CID</button>
						</div>	
		    		</div>
		    	</div>
			</div>

			<div class="panel panel-body" style="background-color:  #e6f2ff;">
		    	<div class="row rp1" style="padding-left: 10px;">
		    		<div class="col-md-3 col-xs-6" style="padding-left: 0px;">
		    			<!-- -->
					    <label class="label_fonte control-label" for="sintomatologia" id="lbl_sintomatologia">Sintomatologia:</label>
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

		    		<div class="col-md-3 col-xs-6" style="padding-left: 0px;" >
					    <!-- -->
					    <label class="label_fonte control-label" id="lbl_doenca_maligna">Doença Maligna: </label>
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

		    		<div class="col-md-3 col-xs-6" style="padding-left: 0px;">
		    			<!-- -->
					    <label class="label_fonte control-label" id="lbl_doenca_associada">Doenças Associadas: </label>
					    <ul>
					    	@for ($i = 0; $i < 4; $i++)
						    <li>
							    @if($d_associadas[$i]['cod_doenca_associada'] === 1)
							    	<input type="checkbox" id="doenca_associada{{ $d_associadas[$i]['cod_doenca_associada'] }}" class='name' name="doenca_associada" value="{{ $d_associadas[$i]['cod_doenca_associada'] }}" onchange="doenca_ass_checkbox()" checked> {{$d_associadas[$i]['dsc_doenca_associada']}}
							    @else
								    <input type="checkbox" id="doenca_associada{{ $d_associadas[$i]['cod_doenca_associada'] }}" class='name' name="doenca_associada" onchange="doenca_ass_checkbox2()" value="{{ $d_associadas[$i]['cod_doenca_associada'] }}"> {{$d_associadas[$i]['dsc_doenca_associada']}}
							    @endif
							</li>
						    @endfor
					    </ul>
		    		</div>

		    		<div class="col-md-3 col-xs-6" style="padding-left: 0px;">
		    			<!-- -->
					    <label class="label_fonte control-label" for="operacao_porte" id="lbl_operacao_porte">Porte da operaçao: </label>
					    <ul>
					    	@foreach($porte_operacao as $item)
							    <li><input type="radio" name="operacao_porte" id="operacao_porte{{ $item['cod_porte_operacao'] }}" value="{{ $item['cod_porte_operacao'] }}">{{ $item['dsc_porte_operacao'] }}<br/></li>
					    	@endforeach
					    </ul>
		    		</div>

		    	</div>
		    	<div class="row rp1" style="padding-left: 0px;">
		    		<div class="col-md-2" style="max-width: 100%;">
		    			<!-- RETIRAR O TEXTAREA  PARA PADRONIZAR OS DADOS!! LINKAR COM A CLINICA! -->
					    <label class="label_fonte control-label" for="proced_prop" id="lbl_proced_prop">Procedimentos: </label>
		    		</div>
		    		<div class="col-md-10" style="white-space:nowrap">
		    			<div class="row">
			    			<div class="proced_prop-list">
			    				<div class="col-md-11 input-group proced_prop-input" style="max-width: 100%;">
			    					<!-- Dados com base no AGHU - IMPORTAR!! -->
			    					<select name="proced_prop1" id="proced_prop1" class="proced_prop form-control" style=""></select>
			    					<label class="aviso_aghu control-label" class="aviso_aghu" id="lbl_aviso_proced_prop" style="display: block; text-align: left;">*Se não existir o procedimento, cadastrar primeiro no AGHU. </label>
			    				</div>
							</div>
						</div>
						<button type="button" class="btn btn-success btn-sm btn-add-proced_prop" style="height: 10%;"><span class="glyphicon glyphicon-plus"></span> Adicionar outro procedimento</button>
		    		</div>
		    	</div>

		    	<!--  -->
		    	<div class="row rp1" style="">
		    		<div class="col-md-4 col-xs-6">
		    			<label class="label_fonte control-label" for="anestesia" id="lbl_anestesia">Tipo de Anestesia: </label>
		    			<div class="row">
			    			<div class="col-md-12 anestesia-list" style="">
			    				<div class="col-md-12 input-group anestesia-input" style="">
			    					<select name="anestesia1" id="anestesia1" class="anestesia form-control">
								    	<option disabled selected value=""></option>
								    	@foreach($anestesias as $item)
									    	<option value="{{ $item['cod_anestesia'] }}">{{ $item['dsc_anestesia'] }}
									    	</option>
								    	@endforeach
								    </select>
			    					<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_anestesia" style="width: 95%;">*Se não existir o anestesia, cadastrar primeiro no AGHU. </label>
			    				</div>
							</div>
						<button type="button" class="btn btn-success btn-sm btn-add-anestesia" style="height: 10%; margin-left: 5%;"><span class="glyphicon glyphicon-plus"></span> Adicionar anestesia</button>
						</div>
		    		</div>

		    		<!--  -->
		    		<div class="col-md-3 col-sx-6">
		    			<label class="label_fonte control-label" for="exames_trans_op" id="lbl_exames_trans_op">Exames trans-operatório: </label>
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
		    		<div class="col-md-5 col-xs-7" style="">
		    			<label class="label_fonte" id="hemoderivados">Hemoderivados (Unid): </label>
		    			<div class="row">
		    				<div class="col-md-6 col-xs-5">
				    			<label class="label_sm_fnt br" for="conc_hemacias" id="lbl_conc_hemacias">Conc.Hemacias: </label>
								<input type="text" name="conc_hemacias" id="conc_hemacias" style="width:20%;" value='0'>
							</div>

							<div class="col-md-6 col-xs-5">
								<label class="label_sm_fnt br" for="plasma" id="lbl_plasma">Plasma: </label>
								<input type="text" name="plasma" id="plasma" style="width:20%;" value='0'>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 ">
								<label class="label_sm_fnt br" for="plaquetas" id="lbl_plaquetas">Plaquetas: </label>
								<input type="text" name="plaquetas" id="plaquetas" style="width:20%;" value='0'>
							</div>

							<div class="col-md-6">
								<label class="label_sm_fnt br" for="crio_precipitado" id="lbl_crio_precipitado">Crio Precipitado: </label>
								<input type="text" name="crio_precipitado" id="crio_precipitado" style="width:20%;" value='0'>
							</div>
						</div>
					</div>
		    	</div>

				<!--  -->
				<div class="row rp1">
					<div class="col-xs-6">
		    			<label class="label_fonte control-label" for="material" id="lbl_material">Material de órtese ou prótese: </label>
		    			<select name="material[]" id="material" class="material form-control" multiple="multiple">
					    	@foreach($material as $item)
						    	<option value="{{ $item['cod_material'] }}">{{ $item['dsc_material'] }}
						    	</option>
					    	@endforeach
					    </select>
						<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_material" style="width: 95%;">*Se não existir o material, cadastrar primeiro no AGHU. </label>
		    		</div>

					<!-- -->
					<div class="col-xs-6">
						<label class="label_fonte control-label" for="equipamento_cirg" id="lbl_equipamento_cirg">Instrumentos e/ou equipamentos específicos: </label>
						<select name="equipamento_cirg[]" id="equipamento_cirg" class="equipamento_cirg form-control" multiple="multiple">
					    	@foreach($equipamento_cirg as $item)
						    	<option value="{{ $item['cod_equipamento_cirg'] }}">{{ $item['dsc_equipamento_cirg'] }}
						    	</option>
					    	@endforeach
					    </select>
						<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_material" style="width: 95%;">*Se não existir o equipamento, cadastrar primeiro no AGHU. </label>
					</div>
				</div>

				<!-- -->
				<div class="row rp1 text-center">
					<div class="col-md-12">
						<label class="label_fonte br" for="obs" id="lbl_obs">Observações: </label>
						<textarea rows="3" name="observacao" cols="130" id="observacao">
						</textarea>
					</div>
				</div>
				<div class="row rp1" style="padding-bottom: 1%;">
					<div style="float:left; margin-left:17%;">
						<input type="button" class="btn btn-default" onclick="window.location='{{ route("home") }}'" name="cancelar" value='Cancelar'/>
					</div>

					<div style="float:right; margin-right:17%;">
						<input type="submit" class="btn btn-primary" style="" name="submit" value='Cadastrar Pedido'/>
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
   	$(document).ajaxStart(function () {
    	$('#loadModal').modal('show');
    	document.getElementById("block_loading_page").disabled = true;
    	}).ajaxStop(function () {
        $('#loadModal').modal('hide');
        document.getElementById("block_loading_page").disabled = false;
    	});
    	
    	$("#data_pedido").datepicker({});

    	$(".cid").select2({
            placeholder: "Descreva todos os CIDs relacionados",
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
                                id: item.cod_cid
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $("#cirurgiao").select2({
            placeholder: "Selecione o cirurgião responsável",
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

        $(".proced_prop").select2({
            placeholder: "Descreva o(s) procedimento(s) realizado(s)",
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
        	placeholder: "Selecione a clínica responsável."
        });

        $("#convenio").select2();

        $("#anestesia1").select2({
			placeholder: "Entre com a(s) Anestesia(s)"
		});

        $("#material").select2({
			placeholder: "Descreva todos os materiais de órtese ou prótese utilizados no procedimento."
		});

		$("#equipamento_cirg").select2({
			placeholder: "Descreva todos os instrumentos ou equipamentos utilizados no procedimento."
		});

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
    	console.log(token.val());

		$.ajax({
		    url: url,
		    dataType:'json',
		    type: 'GET',
		    delay: 10000,
		    headers: {
    			'X-CSRF-TOKEN': token.val()
			},
		    success: function(data){
		    		$('#nome_paciente').val(data[0].txt_nome);
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

	$('.btn-add-proced_prop').click(function(){
		var index = $('.proced_prop-input').length + 1;

		$('.proced_prop-list').append(''+
			'<div class="col-xs-12 input-group proced_prop-input">'+
			'<select name="proced_prop'+index+'" id="proced_prop'+index+'" class="proced_prop form-control" style="width: 95%;"></select>'+
			'<span>'+
			'<button style="width=98%;" class="btn btn-danger btn-remove-proced_prop" type="button"><span class="glyphicon glyphicon-minus"></span></button>'+
			'</span>'+
			'<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_proced_prop" style="display: block;">*Se não existir o procedimento, cadastrar primeiro no AGHU. </label>'+
			'</div>'
			);
		$(".proced_prop").select2({
			placeholder: "Descreva o(s) procedimento(s) realizado(s)",
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
	});

	$('.btn-add-anestesia').click(function(){
		var index = $('.anestesia-input').length + 1;

		$('.anestesia-list').append(''+
			'<div class="col-xs-11 input-group anestesia-input" id="div_anestesia_'+index+'" style="white-space:nowrap">'+
			'<select name="anestesia'+index+'" id="anestesia'+index+'" class="anestesia form-control" style=""></select>'
		);

		$('#anestesia'+index).append('<option disabled selected value=""></option>');

		@foreach($anestesias as $item)
			$('#anestesia'+index).append('<option value="{{ $item["cod_anestesia"] }}">{{ $item["dsc_anestesia"] }}</option>');
		@endforeach

		$('#div_anestesia_'+index).append(''+
			'<span>'+
			'<button style="width=92%;" class="btn btn-danger btn-remove-anestesia" type="button"><span class="glyphicon glyphicon-minus"></span></button>'+
			'</span>'+
			'<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_anestesia" style="display: block;">*Se não existir o anestesia, cadastrar primeiro no AGHU. </label>'+
			'</div>'
		);

		$(".anestesia").select2({
			placeholder: "Entre com a(s) Anestesia(s)"
		});

	});

	

	$('.btn-add-cid').click(function(){
		var index = $('.cid-input').length + 1;

		$('.cid-list').append(''+
			'<div class="col-xs-11 input-group cid-input">'+
			'<select name="cid'+index+'" id="cid'+index+'" class="cid form-control" style="width: 95%;"></select>'+
			'<span>'+
			'<button style="width=98%;" class="btn btn-danger btn-remove-cid" type="button"><span class="glyphicon glyphicon-minus"></span></button>'+
			'</span>'+
			'<label class="aviso_aghu" class="aviso_aghu" id="lbl_aviso_cid" style="display: block;">*Se não existir o CID, cadastrar primeiro no AGHU. </label>'+
			'</div>'
			);

		$(".cid").select2({
			placeholder: "Descreva todos os CIDs relacionados.",
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
								id: item.cod_cid
							}
						})
					};
				},
				cache: true
			}
		});
	});
});
</script>
@endsection
