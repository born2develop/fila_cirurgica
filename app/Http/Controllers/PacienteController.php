<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Paciente;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function find($prontuario)
    {
        /**
        *  getting database data
        */ 
        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");
        //$paciente = Paciente::where('cod_paciente', '=', $id)
        //  ->get(['num_prontuario', 'num_cartao_saude', 'txt_sexo', 'dte_nascimento', 'txt_nome_mae']);

        $paciente = DB::table('vwm_aip_pacientes_aghu')
        ->where('num_prontuario', '=', $prontuario)
        ->get(['cod_paciente', 'txt_nome', 'num_prontuario', 'num_cartao_saude', 
        'txt_sexo', 'dte_nascimento', 'txt_nome_mae', 'num_ddd_fone_residencial', 'num_fone_residencial',
        'num_ddd_fone_recado', 'txt_fone_recado']);

        DB::select("select public.dblink_disconnect('postgres');");

        $paciente = collect($paciente)->map(function($x){ return (array) $x; })->toArray();

        /**
        * using carbon extension for dates
        */
        $data_nascimento = new Carbon(substr($paciente[0]['dte_nascimento'], 0, 10));

        $paciente[0]['dte_nascimento'] = $data_nascimento->format('d/m/Y');
        return response()->json($paciente);
    }

    public function getCodPaciente($prontuario)
    {
        /**
        *  getting database data
        */ 
        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

        $paciente = DB::table('vwm_aip_pacientes_aghu')
        ->where('num_prontuario', '=', $prontuario)
        ->get(['cod_paciente']);

        DB::select("select public.dblink_disconnect('postgres');");

        $paciente = collect($paciente)->map(function($x){ return (array) $x; })->toArray();

        return response()->json($paciente);
    }

    public function find_by_cod_paciente($cod_paciente)
    {
        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");
        //$paciente = Paciente::where('cod_paciente', '=', $id)
        //  ->get(['num_prontuario', 'num_cartao_saude', 'txt_sexo', 'dte_nascimento', 'txt_nome_mae']);

        $paciente = DB::table('vwm_aip_pacientes_aghu')
        ->whereIn('cod_paciente', $cod_paciente)
        ->get(['cod_paciente', 'num_prontuario', 'txt_nome']);

        DB::select("select public.dblink_disconnect('postgres');");

        $paciente = collect($paciente)->map(function($x){ return (array) $x; })->toArray();

        return $paciente;
    }
}