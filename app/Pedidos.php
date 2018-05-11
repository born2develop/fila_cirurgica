<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    // protected $connection = 'connection-name'; // Set another connection
    protected $table = 'tab_pedido'; // Set table name (rather than "cids")

    protected $primaryKey = 'cod_pedido';

    // protected $dateFormat = ''; // Set a date format

	public $timestamps = false; // Set off creat_at and update_at tables

	protected $fillable = 
	[
		'cod_tipo_operacao',
		'cod_tipo_centro_cirurgico',
		'cod_paciente',
		'cod_especialidade',
		'txt_enf_leito_apt',
		'cod_convenio',
		'txt_diagnostico_principal',
		'cod_cid',
		'cod_sintomatologia',
		'cod_doenca_maligna',
		'cod_doenca_associada',
		'txt_procedimento_proposto',
		'cod_porte_operacao',
		'cod_ciurgiao',
		'txt_auxiliares',
		'cod_anestesia',
		'cod_exames_trans_op',
		'txt_radiologico_descricao',
		'num_conc_hemacias',
		'num_plasma',
		'num_plaquetas',
		'num_crio_precipitado',
		'txt_material_ortese_protese',
		'txt_instrumento_equip_espec',
		'txt_observacoes',
		'dte_cadastro',
		'num_ddd_telefone_atual',
		'num_telefone_atual',
		'bln_cirurgia_realizada_old',
		'dte_pedido',
		'dth_hora_registro',
		'cod_encaminhamento'
	];
}