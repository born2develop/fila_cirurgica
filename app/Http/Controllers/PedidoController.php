<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cid;
use App\Pedidos;
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

        //dd($this->dataDisplay['material']);

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
            'encaminhamento' => $this->dataDisplay['encaminhamento'],
            'material' => $this->dataDisplay['material'],
            'equipamento_cirg' => $this->dataDisplay['equipamento_cirg']
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
        $pedido = new Pedidos;
        $input = $request->all();
        $timestamp_input = new Carbon();

        dd($input);

        // INSERINDO DADOS NO BD
        $pedido->cod_tipo_operacao = $input['operacao'];
        $pedido->cod_centro_cirurgico = $input['tipo_cirurgia'];
        $pedido->cod_paciente = $input['cod_paciente']; // pegar dados do banco
        $pedido->cod_especialidade = $input['especialidade']; 
        $pedido->cod_convenio = $input['convenio'];
        $pedido->cod_sintomatologia = $input['sintomatologia'];
        $pedido->cod_doenca_maligna = $input['doenca_maligna'];
        $pedido->cod_porte_operacao = $input['operacao_porte'];
        $pedido->cod_cirurgiao = $input['cirurgiao'];
        $pedido->cod_exames_trans_op = $input['exames_trans_op'];
        $pedido->num_conc_hemacias = $input['conc_hemacias'];
        $pedido->num_plasma = $input['plasma'];
        $pedido->num_plaquetas = $input['plaquetas'];
        $pedido->num_crio_precipitado = $input['crio_precipitado'];
        $pedido->txt_observacoes = $input['observacao'];
        $pedido->cod_encaminhamento = $input['encaminhamento'];
        //$pedido->dth_hora_registro = $timestamp_input->toDateTimeString();
        //$pedido->dte_pedido = $input['data_pedido'];

        $pedido->save();

        // INSERINDO DADOS DO TIPO ARRAY DO FORMULÁRIO NO BANCO DE DADOS (MATERIAL E EQUIPAMENTO)
        foreach($material as $item){
            // CRIAR RLC ENTRE MATERIAL E PEDIDO
        }

        // -- ANALISANDO QUANTIDADES DE CIDS NO PROJETO
        $countCids = 1;
        $countProcedimentos = 1;
        $countAnestesias = 1;

        for($i=0; $i<=count($input); $i++)
        {
            if(isset($input['cid'.$countCids]))
                $countCids++;


            if(isset($input['proced_prop'.$countProcedimentos]))
                $countProcedimentos++;

            if(isset($input['anestesia'.$countAnestesias]))
                $countAnestesias++;
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
        for($i=1; $i<$countAnestesias; $i++)
        {
            DB::table('rlc_pedido_anestesia')->insert([
                ['cod_pedido' => $pedido->cod_pedido,
                 'cod_anestesia' => $input['anestesia'.$i]]
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
        // --

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

        $pedido = Pedidos::find($id);

        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

        $nome = DB::table('formulario.vwm_aip_pacientes_aghu')
            ->select('txt_nome')
            ->where('cod_paciente', '=', $pedido['attributes']['cod_paciente'])
            ->first();

        $prontuario = DB::table('formulario.vwm_aip_pacientes_aghu')
            ->select('num_prontuario')
            ->where('cod_paciente', '=', $pedido['attributes']['cod_paciente'])
            ->first();
        $rlc_doenca_associada = DB::table('formulario.rlc_pedido_doenca_associada')
            ->select('cod_doenca_associada')
            ->where('cod_pedido', '=', $pedido['attributes']['cod_pedido'])
            ->get();

        $rlc_pedido_cid = DB::table('formulario.rlc_pedido_cid')
            ->join('formulario.vpc_cid_aghu', 'formulario.rlc_pedido_cid.cod_cid', 'formulario.vpc_cid_aghu.cod_cid')
            ->select('formulario.rlc_pedido_cid.cod_cid', 'formulario.vpc_cid_aghu.dsc_cid')
            ->where('cod_pedido', '=', $pedido['attributes']['cod_pedido'])
            ->get();

        $rlc_pedido_proced_cirurgico = DB::table('formulario.rlc_pedido_proced_cirurgico')
            ->join('formulario.vpc_procedimento_cirurgico_aghu', 'formulario.rlc_pedido_proced_cirurgico.cod_proced_cirurgico', 'formulario.vpc_procedimento_cirurgico_aghu.cod_proced_cirurgico')
            ->select('formulario.rlc_pedido_proced_cirurgico.cod_proced_cirurgico', 'formulario.vpc_procedimento_cirurgico_aghu.dsc_proced_cirurgico')
            ->where('cod_pedido', '=', $pedido['attributes']['cod_pedido'])
            ->get();

        $rlc_pedido_anestesia = DB::table('formulario.rlc_pedido_anestesia')
            ->join('formulario.vpc_anestesia_aghu', 'formulario.rlc_pedido_anestesia.cod_anestesia',
                'formulario.vpc_anestesia_aghu.cod_anestesia')
            ->select('formulario.rlc_pedido_anestesia.cod_anestesia', 'formulario.vpc_anestesia_aghu.dsc_anestesia')
            ->where('cod_pedido', '=', $pedido['attributes']['cod_pedido'])
            ->get();

        DB::select("select public.dblink_disconnect('postgres');");

        $nome = collect($nome)->map(function($x){ return (array) $x; })->toArray();
        $prontuario = collect($prontuario)->map(function($x){ return (array) $x; })->toArray();
        $rlc_doenca_associada = collect($rlc_doenca_associada)->map(function($x){ return (array) $x; })->toArray();
        $rlc_pedido_cid = collect($rlc_pedido_cid)->map(function($x){ return (array) $x; })->toArray();
        $rlc_pedido_proced_cirurgico = collect($rlc_pedido_proced_cirurgico)->map(function($x){ return (array) $x; })->toArray();
        $rlc_pedido_anestesia = collect($rlc_pedido_anestesia)->map(function($x){ return (array) $x; })->toArray();

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
            // Injetando dados referente ao pedido na view
            'pedido' => $pedido['attributes'],
            'rlc_doenca_associada' => $rlc_doenca_associada,
            'rlc_pedido_cid' => $rlc_pedido_cid,
            'rlc_pedido_proced_cirurgico' => $rlc_pedido_proced_cirurgico,
            'rlc_pedido_anestesia' => $rlc_pedido_anestesia,
            'cod_prontuario' => $prontuario['num_prontuario'][0],
            'nome' => $nome['txt_nome'][0],
            'encaminhamento' => $this->dataDisplay['encaminhamento']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(isset($request->cod_pedido)){
            $pedido = DB::table('formulario.tab_pedido')
                    ->select('*')
                    ->where('cod_pedido', $request->cod_pedido)
                    ->get();
        }
        else{
            return 'Erro na atualização dos dados!';
        }

        // Carrega os dados no BD
        $pedido->cod_tipo_operacao = $input['operacao'];
        $pedido->cod_centro_cirurgico = $input['tipo_cirurgia'];
        $pedido->cod_paciente = $input['cod_paciente']; // pegar dados do banco
        $pedido->cod_especialidade = $input['especialidade']; 
        $pedido->cod_convenio = $input['convenio'];
        $pedido->cod_sintomatologia = $input['sintomatologia'];
        $pedido->cod_doenca_maligna = $input['doenca_maligna'];
        $pedido->cod_porte_operacao = $input['operacao_porte'];
        $pedido->cod_cirurgiao = $input['cirurgiao'];
        $pedido->cod_exames_trans_op = $input['exames_trans_op'];
        $pedido->num_conc_hemacias = $input['conc_hemacias'];
        $pedido->num_plasma = $input['plasma'];
        $pedido->num_plaquetas = $input['plaquetas'];
        $pedido->num_crio_precipitado = $input['crio_precipitado'];
        $pedido->txt_observacoes = $input['observacao'];
        $pedido->cod_encaminhamento = $input['encaminhamento'];
        //$pedido->dth_hora_registro = $timestamp_input->toDateTimeString();
        //$pedido->dte_pedido = $input['data_pedido'];

        $pedido->save();

        $countCids = 1;
        $countProcedimentos = 1;
        $countAnestesias = 1;

        for($i=0; $i<=count($input); $i++)
        {
            if(isset($input['cid'.$countCids]))
                $countCids++;


            if(isset($input['proced_prop'.$countProcedimentos]))
                $countProcedimentos++;

            if(isset($input['anestesia'.$countAnestesias]))
                $countAnestesias++;
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

        for($i=1; $i<$countAnestesias; $i++)
        {
            DB::table('rlc_pedido_anestesia')->insert([
                ['cod_pedido' => $pedido->cod_pedido,
                 'cod_anestesia' => $input['anestesia'.$i]]
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

    public function pesquisarPedidos()
    {
         $situacoes = DB::table('formulario.opc_situacao')
            ->select('cod_situacao', 'dsc_situacao')
            ->get();

        $situacoes = collect($situacoes)->map(function($x){ return (array) $x; })->toArray();

        return view('pedidoCirurgia.mostrar_pedidos')->with([
            'situacoes' => $situacoes
        ]);
    }

    public function getPedidosByCodPaciente($cod_paciente)
    {
        $pedidos = DB::table('formulario.tab_pedido')
            ->select('cod_pedido', 'dsc_especialidade', 'dsc_centro_cirurgico', 'dsc_situacao', 'formulario.tab_pedido.cod_situacao')
            ->where('cod_paciente', '=', $cod_paciente)
            ->leftJoin('formulario.opc_situacao', 'formulario.tab_pedido.cod_situacao', 'formulario.opc_situacao.cod_situacao')
            ->join('formulario.opc_especialidade', 'formulario.tab_pedido.cod_especialidade', 'formulario.opc_especialidade.cod_especialidade')
            ->join('formulario.opc_centro_cirurgico', 'formulario.tab_pedido.cod_centro_cirurgico', 'formulario.opc_centro_cirurgico.cod_centro_cirurgico')
            ->get();

        $pedidos = collect($pedidos)->map(function($x){ return (array) $x; })->toArray();
        
        foreach($pedidos as $index=>$pedido)
        {
            foreach($pedido as $field=>$valor)
            {
                if($valor === null)
                {
                    $pedidos[$index][$field] = 'Sem situação';
                }
            }
        }

        return response()->json($pedidos);
    }

    // Set atribute to get general data
    public function getDataToDisplay()
    {
        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

        $anestesias = DB::table('formulario.vpc_anestesia_aghu')
            ->select('cod_anestesia', 'dsc_anestesia')
            ->get();

        $material = DB::table('formulario.vpc_mat_ortese_prot_cirg_aghu')
            ->select('cod_material', 'dsc_material')
            ->get();

        $equipamento_cirg = DB::table('formulario.vpc_equipamento_cirg_aghu')
            ->select('cod_equipamento_cirg', 'dsc_equipamento_cirg')
            ->get();

        DB::select("select public.dblink_disconnect('postgres');");

        $convenios = DB::table('formulario.opc_convenio')
            ->select('cod_convenio', 'dsc_convenio')
            ->get();

        $especialidades = DB::table('formulario.opc_especialidade')
            ->select('cod_especialidade', 'dsc_especialidade')
            ->orderBy('dsc_especialidade', 'asc')
            ->get();

        $d_associadas = DB::table('formulario.vpc_doenca_associada')
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

        $centro_cirurgico = DB::table('formulario.opc_centro_cirurgico')
            ->select('cod_centro_cirurgico', 'dsc_centro_cirurgico')
            ->get();

        $exames_trans_op = DB::table('formulario.opc_exames_trans_operatorio')
            ->select('cod_exames_trans_op', 'dsc_exames_trans_op')
            ->get();

        $encaminhamento = DB::table('formulario.opc_encaminhamento')
            ->select('cod_encaminhamento', 'dsc_encaminhamento')
            ->get();

        $date = DB::select('SELECT current_date');

        // stdClass to array via json
        $exames_trans_op = json_decode(json_encode($exames_trans_op), true);
        $date = json_decode(json_encode($date), true);

        $data_atual = new Carbon(substr($date[0]['date'], 0, 10));
        
        $data_atual = $data_atual->format('d/m/Y');

        // TypeCast -> Query object to Array
        $anestesias = collect($anestesias)->map(function($x){ return (array) $x; })->toArray();
        $material = collect($material)->map(function($x){ return (array) $x; })->toArray();
        $equipamento_cirg = collect($equipamento_cirg)->map(function($x){ return (array) $x; })->toArray();
        $convenios = collect($convenios)->map(function($x){ return (array) $x; })->toArray();
        $d_associadas = collect($d_associadas)->map(function($x){ return (array) $x; })->toArray();
        $d_malignas = collect($d_malignas)->map(function($x){ return (array) $x; })->toArray();
        $porte_operacao = collect($porte_operacao)->map(function($x){ return (array) $x; })->toArray();
        $sintomatologia = collect($sintomatologia)->map(function($x){ return (array) $x; })->toArray();
        $centro_cirurgico = collect($centro_cirurgico)->map(function($x){ return (array) $x; })->toArray();
        $tipo_operacao = collect($tipo_operacao)->map(function($x){ return (array) $x; })->toArray();
        $especialidades = collect($especialidades)->map(function($x){ return (array) $x; })->toArray();
        $encaminhamento = collect($encaminhamento)->map(function($x){ return (array) $x; })->toArray();
        
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
            "data_atual" => $data_atual,
            "encaminhamento" => $encaminhamento,
            "material" => $material,
            "equipamento_cirg" => $equipamento_cirg
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

            $search = strtoupper($request->q);

            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

            /*$cids = Cid::where('dsc_cid','LIKE',"%$search%")
                ->orderBy('cod_cid', 'asc')
                ->take(10)
                ->get(["cod_cid as id", 'dsc_cid as text']);*/

            $cids = Cid::where('txt_codigo','LIKE',"%$search%")
                ->orWhere('dsc_cid','LIKE',"%$search%")
                ->orderBy('cod_cid', 'asc')
                ->take(10)
                ->get(["txt_codigo as id", 'dsc_cid as text', 'cod_cid']);


            DB::select("select public.dblink_disconnect('postgres');");
        }

        return response()->json($cids);
    }

    public function dataAjaxServidores(Request $request)
    {
        $servidor = [];


        if($request->has('q')){

            $search = strtoupper($request->q);

            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

            $servidor = DB::select(DB::raw("SELECT cod_pessoa as id, txt_nome_pessoa as text FROM formulario.vis_pessoa_servidores_aghu
                                        WHERE CAST(cod_pessoa AS TEXT) LIKE '$search%'
                                        OR txt_nome_pessoa LIKE '$search%' 
                                        limit 10")
                                        );

            DB::select("select public.dblink_disconnect('postgres');");
        }

        return response()->json($servidor);
    }

    public function dataAjaxProcedimentosCirurgicos(Request $request)
    {
        $procedimentos = [];


        if($request->has('q')){

            $search = strtoupper($request->q);
            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

            $procedimentos = DB::select(DB::raw("SELECT cod_proced_cirurgico as id, dsc_proced_cirurgico as text FROM formulario.vpc_procedimento_cirurgico_aghu
                                        WHERE CAST(cod_proced_cirurgico AS TEXT) LIKE '$search%'
                                        OR dsc_proced_cirurgico LIKE '$search%' 
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
            $search = strtoupper($request->q);

            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

            $pacientes = DB::select(DB::raw("SELECT num_prontuario as id, txt_nome as text FROM formulario.vwm_aip_pacientes_aghu 
                                        WHERE CAST(num_prontuario AS TEXT) LIKE '$search%'
                                        OR txt_nome LIKE '$search%' 
                                        limit 10")
                                        );

            DB::select("select public.dblink_disconnect('postgres');");
        }

        return response()->json($pacientes);
    }

}
