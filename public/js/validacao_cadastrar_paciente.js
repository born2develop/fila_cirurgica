function ValidateForm()
{
	// id dos campos do tipo input type = text
	var campos_text = ["nome_mae", "data_nascimento", "sus_id", "conc_hemacias", "plasma", 
		"plaquetas", "crio_precipitado"];

	// id dos campos do tipo input type = radio
	var campos_radio = ["operacao", "tipo_cirurgia", "sexo", "sintomatologia", "doenca_maligna", 
		"operacao_porte", "anestesia", "exames_trans_op"];

	// id dos campos do tipo input type = checkbox
	var campos_checkbox = ["doenca_associada1", "doenca_associada2", "doenca_associada3", "doenca_associada4"];

	// id dos campos do tipo select
	var campos_select = ["convenio", "prontuario", "especialidade"];

	var nomeCampo, cont;

	var isValid = true;


	// Loop nos campos input text
	for(cont = 0; cont < campos_text.length; cont++) {
		nomeCampo = campos_text[cont];
		// Condição de preenchimento
		if(document.forms["inserir_fila"][nomeCampo].value == ""){
			
			// Altera a cor dos labels caso não preenchido
			document.getElementById("lbl_"+nomeCampo).style.color = "red";
			document.getElementById(nomeCampo).focus();
			isValid = false;
		}
		else
			// Volta a cor para preto caso já preenchido
			document.getElementById("lbl_"+nomeCampo).style.color = "#636b6f";
	}

	// Loop nos campos input radio
	for(cont = 0; cont < campos_radio.length; cont++) {
		nomeCampo = campos_radio[cont];
		// Condição de preenchimento
		if(document.forms["inserir_fila"][nomeCampo].value == "")
		{
			// Altera a cor dos labels caso não preenchido
			document.getElementById("lbl_"+nomeCampo).style.color = "red";
			document.getElementById("lbl_"+nomeCampo).focus();
			isValid = false;
		}
		else
			// Volta a cor para preto caso já preenchido
			document.getElementById("lbl_" + nomeCampo).style.color = "#636b6f";
	}


	// Validando múltiplos telefones
	var qtd_cid = $('.cid-input').length;
	for(var i = 1; i<=qtd_cid; i++)
	{
		if(document.getElementById("cid"+i) != null) {
			if(document.forms["inserir_fila"]["cid"+i].value.replace(/\s/g,'') == "" 
				|| /\D/.test(document.forms["inserir_fila"]["cid"+i].value))
			{
				document.getElementById("lbl_cid").style.color = "red";
				document.getElementById("cid"+i).focus();
				isValid = false;
			}
			else
				document.getElementById("lbl_cid").style.color = "#636b6f";
		}
		else{
				document.getElementById("lbl_cid").style.color = "red";
				document.getElementById("cid"+i).focus();
				isValid = false;
		}
	}

	var ok = false;
	// Loop nos campos input checkbox
	for(cont = 0; cont < campos_checkbox.length; cont++) {
		nomeCampo = campos_checkbox[cont];
		// Condição de preenchimento
		if(document.forms["inserir_fila"][nomeCampo].checked)
				var ok = true;
	}

	if(!ok)
	{
		// Altera a cor dos labels caso não preenchido
		document.getElementById("lbl_doenca_associada").style.color = "red";
		document.getElementById("lbl_doenca_associada").focus();
		isValid = false;
	}
	else
	{
		// Volta a cor para preto caso já preenchido
		document.getElementById("lbl_doenca_associada").style.color = "#636b6f";
	}
			

	
	// Valida o select
	for(cont = 0; cont < campos_select.length; cont++)
	{
		nomeCampo = campos_select[cont];
		if(document.forms["inserir_fila"][nomeCampo].value == "")
		{
			document.getElementById("lbl_"+nomeCampo).style.color = "red";
			document.getElementById(nomeCampo).focus();
			isValid = false;
		}
		else
			document.getElementById("lbl_" + nomeCampo).style.color = "#636b6f";
	}
	
	if(!isValid) {
		alert("Favor preencher corretamente todos os campos obrigatórios!");
		return false;
	}
	alert("Pedido de cirurgia cadastrado!" +
		"\nProntuário: " + document.forms["inserir_fila"]['prontuario'].value +
		"\nPaciente: " + document.forms["inserir_fila"]['nome_paciente'].value);
	
	return true;
}

function doenca_ass_checkbox(){
	if (document.getElementById('doenca_associada1').checked)
	{
		for(var cont=2; cont<=document.querySelectorAll(".name").length; cont++ )
			document.getElementById("doenca_associada"+cont).checked = false;
	}
}

function doenca_ass_checkbox2(){
	for(var cont=2; cont<=document.querySelectorAll('.name').length; cont++ )
	{	if(document.getElementById('doenca_associada'+cont).checked)
			document.getElementById("doenca_associada1").checked = false;
		else if (document.getElementById('doenca_associada1').checked)
			document.getElementById("doenca_associada"+cont).checked = false;
	}
}