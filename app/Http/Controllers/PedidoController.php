<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cid;
use App\CadastrarPedido;
use Carbon\Carbon;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // store fixed data from db
    private $dataDisplay;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $this->getDataToDisplay();

        return view('pedidoCirurgia.pedido_cirurgia_create',[])->with([
            'anestesias' => $this->dataDisplay['anestesias'],
            'convenios' => $this->dataDisplay['convenios'],
            'd_associadas' => $this->dataDisplay['d_associadas'],
            'd_malignas' => $this->dataDisplay['d_malignas'],
            'porte_operacao' => $this->dataDisplay['porte_operacao'],
            'sintomatologia' => $this->dataDisplay['sintomatologia'],
            'centro_cirurgico' => $this->dataDisplay['centro_cirurgico'],
            'tipo_operacao' => $this->dataDisplay['tipo_operacao'],
            'especialidades' => $this->dataDisplay['especialidades'],
            'exames_trans_op' => $this->dataDisplay['exames_trans_op'],
            'data_atual' => $this->dataDisplay['data_atual'],
            ]);    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pedido = new CadastrarPedido;
        $input = $request->all();

        // Carrega os dados no BD
        $pedido->cod_tipo_operacao = $input['operacao'];
        $pedido->cod_tipo_centro_cirurgico = $input['tipo_cirurgia'];
        $pedido->cod_paciente = $input['cod_paciente']; // pegar dados do banco
        $pedido->cod_especialidade = $input['especialidade']; 
        $pedido->cod_convenio = $input['convenio'];
        $pedido->cod_sintomatologia = $input['sintomatologia'];
        $pedido->cod_doenca_maligna = $input['doenca_maligna'];
        $pedido->cod_porte_operacao = $input['operacao_porte'];
        $pedido->cod_anestesia = $input['anestesia'];
        $pedido->cod_exames_trans_op = $input['exames_trans_op'];
        $pedido->num_conc_hemacias = $input['conc_hemacias'];
        $pedido->num_plasma = $input['plasma'];
        $pedido->num_plaquetas = $input['plaquetas'];
        $pedido->num_crio_precipitado = $input['crio_precipitado'];
        $pedido->txt_observacoes = $input['observacao'];

        // $pedido->cod_doenca_associada = $input['doenca_associada']; // Popular tabela associat.
        // $pedido->cod_doenca_associada = $input['cid']; // Criar tabela associativa!
        // $pedido->txt_radiologico_descricao = $input['']; // Falar com adriano

        /*
         * $pedido->dte_cadastro = $input[''];
         * $pedido->num_ddd_telefone_atual = $input['']; //
         * $pedido->num_telefone_atual = $input['']; // Criar tabela associativa
         * $pedido->bln_cirurgia_realizada_old = $input[''];
         * $pedido->dte_pedido = $input[''];
         * $pedido->dth_hora_registro = $input['']; 

         
         */

        $pedido->save();

        $countCids = 0;
        $countProcedimentos = 0;
        $temp = 1;
        $temp2 = 1;
        for($i=1; $i<count($input); $i++)
        {
            if(isset($input['cid'.$temp]))
            {
                $countCids++;
                $temp++;
            }
            if(isset($input['proced_prop'.$temp2]))
            {    
                $countProcedimentos++;
                $temp2++;
            }
        }

        for($i=1; $i<$countProcedimentos; $i++)
        {
            DB::table('rlc_pedido_proced_cirurgico')->insert([
                ['cod_pedido' => $pedido->cod_pedido,
                 'cod_proced_cirurgico' => $input['proced_prop'.$i]]
                ]);
        }

        for($i=1; $i<$countCids; $i++)
        {
            DB::table('rlc_pedido_cid')->insert([
                ['cod_pedido' => $pedido->cod_pedido,
                 'cod_cid' => $input['cid'.$i]]
                ]);
        }

        $d_associada = "doenca_associada";
        for($i=0; $i<= 4; $i++)
        {

            if(isset($input[$d_associada.$i]))
                DB::table('rlc_pedido_doenca_associada')->insert([
                ['cod_pedido' => $pedido->cod_pedido, 'cod_doenca_associada' => $input[$d_associada.$i]]
                ]);
        }

        return redirect()->route('pedidos.edit', [
            'id' => $pedido->cod_pedido,
            ]);
        
        /**
         * DADOS REMOVIDOS DO BANCO:
         * $pedido->txt_enf_leito_apt = '0';
         * $pedido->txt_diagnostico_principal = 'text area diagnostico';
         * $pedido->txt_procedimento_proposto = $input[''];
         * $pedido->cod_ciurgiao = $input[''];
         * $pedido->txt_auxiliares = $input[''];
         * $pedido->txt_material_ortese_protese = $input[''];
         * $pedido->txt_instrumento_equip_espec = $input[''];
         */    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->getDataToDisplay();

        $pedido = CadastrarPedido::find($id);

        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

        $nome = DB::table('formulario.vis_aip_pacientes_aghu')
            ->select('txt_nome')
            ->where('cod_paciente', '=', $pedido['attributes']['cod_paciente'])
            ->first();

        $prontuario = DB::table('formulario.vis_aip_pacientes_aghu')
            ->select('num_prontuario')
            ->where('cod_paciente', '=', $pedido['attributes']['cod_paciente'])
            ->first();
        $rlc_doenca_associada = DB::table('formulario.rlc_pedido_doenca_associada')
            ->select('cod_doenca_associada')
            ->where('cod_pedido', '=', $pedido['attributes']['cod_pedido'])
            ->get();

        $rlc_pedido_cid = DB::table('formulario.rlc_pedido_cid')
            ->join('formulario.vis_cid_aghu', 'formulario.rlc_pedido_cid.cod_cid', 'formulario.vis_cid_aghu.cod_cid')
            ->select('formulario.rlc_pedido_cid.cod_cid', 'formulario.vis_cid_aghu.dsc_cid')
            ->where('cod_pedido', '=', $pedido['attributes']['cod_pedido'])
            ->get();

        $rlc_pedido_proced_cirurgico = DB::table('formulario.rlc_pedido_proced_cirurgico')
            ->join('formulario.vis_procedimentos_cirurgicos_aghu', 'formulario.rlc_pedido_proced_cirurgico.cod_proced_cirurgico', 'formulario.vis_procedimentos_cirurgicos_aghu.cod_proced_cirurgico')
            ->select('formulario.rlc_pedido_proced_cirurgico.cod_proced_cirurgico', 'formulario.vis_procedimentos_cirurgicos_aghu.txt_proced_cirurgico')
            ->where('cod_pedido', '=', $pedido['attributes']['cod_pedido'])
            ->get();

        DB::select("select public.dblink_disconnect('postgres');");

        $nome = collect($nome)->map(function($x){ return (array) $x; })->toArray();
        $prontuario = collect($prontuario)->map(function($x){ return (array) $x; })->toArray();
        $rlc_doenca_associada = collect($rlc_doenca_associada)->map(function($x){ return (array) $x; })->toArray();
        $rlc_pedido_cid = collect($rlc_pedido_cid)->map(function($x){ return (array) $x; })->toArray();
        $rlc_pedido_proced_cirurgico = collect($rlc_pedido_proced_cirurgico)->map(function($x){ return (array) $x; })->toArray();
        
        return view('pedidoCirurgia.pedido_cirurgia_edit',[])->with([
            // Injetando dados para display na view
            'anestesias' => $this->dataDisplay['anestesias'],
            'convenios' => $this->dataDisplay['convenios'],
            'd_associadas' => $this->dataDisplay['d_associadas'],
            'd_malignas' => $this->dataDisplay['d_malignas'],
            'porte_operacao' => $this->dataDisplay['porte_operacao'],
            'sintomatologia' => $this->dataDisplay['sintomatologia'],
            'centro_cirurgico' => $this->dataDisplay['centro_cirurgico'],
            'tipo_operacao' => $this->dataDisplay['tipo_operacao'],
            'especialidades' => $this->dataDisplay['especialidades'],
            'exames_trans_op' => $this->dataDisplay['exames_trans_op'],
            'data_atual' => $this->dataDisplay['data_atual'],
            // Inejetando dados referente ao pedido na view
            'pedido' => $pedido['attributes'],
            'rlc_doenca_associada' => $rlc_doenca_associada,
            'rlc_pedido_cid' => $rlc_pedido_cid,
            'rlc_pedido_proced_cirurgico' => $rlc_pedido_proced_cirurgico,
            'cod_prontuario' => $prontuario['num_prontuario'][0],
            'nome' => $nome['txt_nome'][0],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function setId(Request $request)
    {
        $id = $request->input('id');
        return redirect()->route('pedidos.edit', $id);
    }

    // Set atribute to get general data
    public function getDataToDisplay()
    {
        $anestesias = DB::table('formulario.opc_anestesia')
            ->select('cod_anestesia', 'dsc_anestesia')
            ->get();

        $convenios = DB::table('formulario.opc_convenio')
            ->select('cod_convenio', 'dsc_convenio')
            ->get();

        $especialidades = DB::table('formulario.opc_especialidade')
            ->select('cod_especialidade', 'dsc_especialidade')
            ->orderBy('dsc_especialidade', 'asc')
            ->get();

        $d_associadas = DB::table('formulario.opc_doenca_associada')
            ->select('cod_doenca_associada', 'dsc_doenca_associada')
            ->get();

        $d_malignas = DB::table('formulario.opc_doenca_maligna')
            ->select('cod_doenca_maligna', 'dsc_doenca_maligna')
            ->get();

        $porte_operacao = DB::table('formulario.opc_porte_operacao')
            ->select('cod_porte_operacao', 'dsc_porte_operacao')
            ->get();

        $tipo_operacao = DB::table('formulario.opc_tipo_operacao')
            ->select('cod_tipo_operacao', 'dsc_tipo_operacao')
            ->get();

        $sintomatologia = DB::table('formulario.opc_sintomatologia')
            ->select('cod_sintomatologia', 'dsc_sintomatologia')
            ->get();

        $centro_cirurgico = DB::table('formulario.opc_tipo_centro_cirurgico')
            ->select('cod_tipo_centro_cirurgico', 'dsc_tipo_centro_cirurgico')
            ->get();

        $exames_trans_op = DB::table('formulario.opc_exames_trans_operatorio')
            ->select('cod_exames_trans_op', 'dsc_exames_trans_op')
            ->get();

        $date = DB::select('SELECT current_date');

        // stdClass to array via json
        $exames_trans_op = json_decode(json_encode($exames_trans_op), true);
        $date = json_decode(json_encode($date), true);

        $data_atual = new Carbon(substr($date[0]['date'], 0, 10));
        
        $data_atual = $data_atual->format('d/m/Y');
        
        // TypeCast -> Query object to Array
        $anestesias = collect($anestesias)->map(function($x){ return (array) $x; })->toArray();
        $convenios = collect($convenios)->map(function($x){ return (array) $x; })->toArray();
        $d_associadas = collect($d_associadas)->map(function($x){ return (array) $x; })->toArray();
        $d_malignas = collect($d_malignas)->map(function($x){ return (array) $x; })->toArray();
        $porte_operacao = collect($porte_operacao)->map(function($x){ return (array) $x; })->toArray();
        $sintomatologia = collect($sintomatologia)->map(function($x){ return (array) $x; })->toArray();
        $centro_cirurgico = collect($centro_cirurgico)->map(function($x){ return (array) $x; })->toArray();
        $tipo_operacao = collect($tipo_operacao)->map(function($x){ return (array) $x; })->toArray();
        $especialidades = collect($especialidades)->map(function($x){ return (array) $x; })->toArray();
        
        $this->dataDisplay = array(
            "anestesias" => $anestesias,
            "convenios" => $convenios,
            "d_associadas" => $d_associadas,
            "d_malignas" => $d_malignas,
            "porte_operacao" => $porte_operacao,
            "sintomatologia" => $sintomatologia,
            "centro_cirurgico" => $centro_cirurgico,
            "tipo_operacao" => $tipo_operacao,
            "especialidades" => $especialidades,
            "exames_trans_op" => $exames_trans_op,
            "data_atual" => $data_atual
        );
    }

    public function cidFind($id)
    {
        $cids = [];

        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

        $cids = Cid::Where('dsc_cid','=', $id)
            ->take(1)
            ->get(["cod_cid as id", 'dsc_cid as text']);


        DB::select("select public.dblink_disconnect('postgres');");
        

        return response()->json($cids);
    }

    /**
     * Implementation of autocomplete Controller
     *
     * @return Query from input data
     */
    public function dataAjaxCid(Request $request)
    {
        $cids = [];


        if($request->has('q')){

            $search = $request->q;

            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

            /*$cids = Cid::where('dsc_cid','LIKE',"%$search%")
                ->orderBy('cod_cid', 'asc')
                ->take(10)
                ->get(["cod_cid as id", 'dsc_cid as text']);*/

            $cids = Cid::whereRaw("CAST(cod_cid AS TEXT) LIKE '$search%'")
                ->orWhere('dsc_cid','LIKE',"%$search%")
                ->orderBy('cod_cid', 'asc')
                ->take(10)
                ->get(["cod_cid as id", 'dsc_cid as text']);


            DB::select("select public.dblink_disconnect('postgres');");
        }

        return response()->json($cids);
    }

    public function dataAjaxProcedimentosCirurgicos(Request $request)
    {
        $procedimentos = [];


        if($request->has('q')){

            $search = $request->q;
            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

            $procedimentos = DB::select(DB::raw("SELECT cod_proced_cirurgico as id, txt_proced_cirurgico as text FROM formulario.vis_procedimentos_cirurgicos_aghu
                                        WHERE CAST(cod_proced_cirurgico AS TEXT) LIKE '$search%'
                                        OR txt_proced_cirurgico LIKE '$search%' 
                                        limit 10")
                                        );

            DB::select("select public.dblink_disconnect('postgres');");
        }

        return response()->json($procedimentos);
    }

    public function dataAjaxDadosPaciente(Request $request)
    {

        $pacientes = [];

        if($request->has('q')){
            $search = $request->q;

            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

            $pacientes = DB::select(DB::raw("SELECT num_prontuario as id, txt_nome as text FROM formulario.vis_aip_pacientes_aghu 
                                        WHERE CAST(num_prontuario AS TEXT) LIKE '$search%'
                                        OR txt_nome LIKE '$search%' 
                                        limit 10")
                                        );

            DB::select("select public.dblink_disconnect('postgres');");
        }

        return response()->json($pacientes);
    }

}
