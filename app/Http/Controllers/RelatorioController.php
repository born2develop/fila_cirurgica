<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pedidos;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Document\Properties;

class RelatorioController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index()
    {
    	return view('relatorios.index');
    }

    public function relatorioJson(Request $request)
    {
        $pedidos = [];
        $input = $request->all();
        //var_dump($input);

        $data_inicio = isset($input['data_inicio']) ? $input['data_inicio'] : null;
        $data_fim = isset($input['data_fim']) ? $input['data_fim'] : null;
        $especialidade = isset($input['especialidade']) ? $input['especialidade'] : null;
        $cirurgiao = isset($input['cirurgiao']) ? $input['cirurgiao'] : null;

        $pedidos = $this->criarRelatorio($data_inicio, $data_fim, $especialidade, $cirurgiao);
        
        return response()->json($pedidos);
    }

    public function criarRelatorio($data_inicio=null, $data_fim=null, $especialidade=null, $cirurgiao=null)
    {
        

        $query = "SELECT cod_pedido, dsc_tipo_operacao, dsc_encaminhamento, num_prontuario, txt_nome, txt_nome_mae, dte_nascimento, num_ddd_telefone_atual, num_telefone_atual, num_cartao_saude, num_idade, txt_sexo, txt_estado_civil, txt_logradouro, txt_bairro, txt_cidade, txt_uf, num_cep, num_fone_residencial, num_ddd_fone_residencial, num_ddd_fone_recado, txt_fone_recado, dsc_especialidade, cod_especialidade, txt_diagnostico_principal, dsc_sintomatologia, dsc_doenca_maligna, dsc_doenca_associada, txt_procedimento_proposto, dsc_porte_operacao, txt_nome_pessoa as txt_cirurgiao, txt_auxiliares, dsc_cid, num_conc_hemacias, num_plasma, num_plaquetas, num_crio_precipitado, dte_pedido, txt_material_ortese_protese, txt_instrumento_equip_espec, txt_observacoes, dth_hora_registro, cod_cirurgiao FROM formulario.vis_relatorio";
        //var_dump($data_inicio, $data_fim, $especialidade, $cirurgiao, !is_null($data_inicio));

        $data_inicio = isset($data_inicio) ? Carbon::createFromFormat('d/m/Y', $data_inicio)->toDateString() : null;
        $data_fim = isset($data_fim) ? Carbon::createFromFormat('d/m/Y', $data_fim)->toDateString() : null;
        //var_dump($data_inicio, $data_fim, $especialidade, $cirurgiao);
        // CRIANDO OS FILTROS DO RELATÓRIO
        // SE APENAS A DATA FOR INFORMADA
        if(!is_null($data_inicio) && !is_null($data_fim) && is_null($especialidade) && is_null($cirurgiao)) {
            $query .= " WHERE dte_pedido BETWEEN '".$data_inicio."' AND '".$data_fim."'";
        }
        // SE APENAS O CIRURGIÃO FOR INFORMADO
        elseif(is_null($data_inicio) && is_null($data_fim) && is_null($especialidade) && !is_null($cirurgiao)){
            $query .= " WHERE cod_cirurgiao = ".$cirurgiao;
        }
        // SE APENAS A ESPECIALIDADE FOR INFORMADA
        elseif(is_null($data_inicio) && is_null($data_fim) && !is_null($especialidade) && is_null($cirurgiao)){
            $query .= " WHERE cod_especialidade = ".$especialidade;
        }
        // SE A DATA E A ESPECIALIDADE FOREM INFORMADOS
        elseif(!is_null($data_inicio) && !is_null($data_fim) && !is_null($especialidade) && is_null($cirurgiao)){
            $query .= " WHERE dte_pedido BETWEEN '".$data_inicio."' AND '".$data_fim."'";
            $query .= " AND cod_especialidade = ".$especialidade;
        }
        // SE A DATA E O CIRURGIÃO FOREM INFORMADOS
        elseif(!is_null($data_inicio) && !is_null($data_fim) && is_null($especialidade) && !is_null($cirurgiao)){
            $query .= " WHERE dte_pedido BETWEEN '".$data_inicio."' AND '".$data_fim."'";
            $query .= " AND cod_cirurgiao = ".$cirurgiao;
        }
        // A DATA E O CIRURGIÃO
        elseif(is_null($data_inicio) && is_null($data_fim) && !is_null($especialidade) && !is_null($cirurgiao)){
            $query .= " WHERE cod_especialidade = ".$especialidade;
            $query .= " AND cod_cirurgiao = ".$cirurgiao;
        }
        // SE A DATA, O CIRURGIÃO E A ESPECILIDADE FOREM INFORMADOS
        elseif(!is_null($data_inicio) && !is_null($data_fim) && !is_null($especialidade) && !is_null($cirurgiao)){
            $query .= " WHERE dte_pedido BETWEEN '".$data_inicio."' AND '".$data_fim."'";
            $query .= " AND cod_especialidade = ".$especialidade;
            $query .= " AND cod_cirurgiao = ".$cirurgiao;
        }
        else {
            ;
        }

        $query .= " ORDER BY cod_pedido DESC";


        unset($data_inicio, $data_fim, $especialidade, $cirurgiao);  

        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

        $pedidos = DB::select(DB::raw($query));

        DB::select("select public.dblink_disconnect('postgres');");
        
        foreach($pedidos as $index=>$pedido)
        {
            foreach($pedido as $field=>$valor)
            {
                if($valor === NULL)
                {
                    $pedidos[$index]->$field = '';
                }

                if($field == 'dte_nascimento')
                {
                    $carbon = new Carbon($valor);
                    $pedidos[$index]->$field = $carbon->toDateString();
                }
            }
        }

        return $pedidos;
    }

    public function filtro()
    {
        $carbon = new Carbon('');
        $hoje = $carbon->toDateString();
        
        $situacoes = DB::table('formulario.opc_situacao')
            ->select('cod_situacao', 'dsc_situacao')
            ->get();

        $especialidades = DB::table('formulario.opc_especialidade')
            ->select('cod_especialidade', 'dsc_especialidade')
            ->orderBy('dsc_especialidade', 'asc')
            ->get();

        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

        $pedidos = DB::table('formulario.vis_relatorio')
                    ->select('cod_pedido', 'dte_pedido', 'dsc_tipo_operacao', 
                    'dsc_encaminhamento', 'num_prontuario', 'txt_nome', 'txt_nome_mae',
                    'dte_nascimento', 'num_ddd_telefone_atual', 'num_telefone_atual')
                    ->where('dte_pedido', '2015-01-14')
                    ->get();

        DB::select("select public.dblink_disconnect('postgres');");
        //dd($pedidos);

        $pedidos = collect($pedidos)->map(function($x){ return (array) $x; })->toArray();
        $especialidades = collect($especialidades)->map(function($x){ return (array) $x; })->toArray();
        $situacoes = collect($situacoes)->map(function($x){ return (array) $x; })->toArray();

        foreach($pedidos as $index=>$pedido)
        {
            $carbon = new Carbon($pedido['dte_nascimento']);
            $pedidos[$index]['dte_nascimento'] = $carbon->toDateString();
        }

        return view('relatorios.filtro')->with([
            'pedidos' => $pedidos,
            'especialidades' => $especialidades,
            'situacoes' => $situacoes
        ]);
    }

    public function excel(Request $request) {
        $relatorio = [];
        $input = $request->all();
        $carbon = new Carbon();

        // var_dump($input);

        $data_inicio = isset($input['data_inicio']) ? $input['data_inicio'] : null;
        $data_fim = isset($input['data_fim']) ? $input['data_fim'] : null;
        $especialidade = isset($input['especialidade']) ? $input['especialidade'] : null;
        $cirurgiao = isset($input['cirurgiao']) ? $input['cirurgiao'] : null;


        $relatorio = $this->criarRelatorio($data_inicio, $data_fim, $especialidade, $cirurgiao);

        $relatorio = json_decode(json_encode($relatorio), true);

        $planilha = new Spreadsheet();
        $propriedades = new Properties();

        $propriedades->setCreator('Matheus Goncalves')
        ->setLastModifiedBy('04994226199')
        ->setTitle('Relatório - Fila Cirúrgica')
        ->setSubject('Relatório da Fila Cirúrgica')
        ->setDescription('Relatório que retorna as cirurgia conforme parâmetros do filtro.')
        ->setKeywords('pedidos cirurgia relatorio')
        ->setCategory('');

        $planilha->setProperties($propriedades);
        
        $planilha->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Pedido')
            ->setCellValue('B1', 'Data do pedido')
            ->setCellValue('C1', 'Operação')
            ->setCellValue('D1', 'Local da cirurgia')
            ->setCellValue('E1', 'Prontuário')
            ->setCellValue('F1', 'Nome')
            ->setCellValue('G1', 'Mãe')
            ->setCellValue('H1', 'Nascimento')
            ->setCellValue('I1', 'Sexo')
            ->setCellValue('J1', 'Telefone contato')
            ->setCellValue('K1', 'Telefone recado')
            ->setCellValue('L1', 'Cartao SUS')
            ->setCellValue('M1', 'Cidade')
            ->setCellValue('N1', 'Logradouro')
            ->setCellValue('O1', 'CEP')
            ->setCellValue('P1', 'Especialidade')
            ->setCellValue('Q1', 'Sintomatologia')
            ->setCellValue('R1', 'Doença Maligna')
            ->setCellValue('S1', 'Conc. hemacias')
            ->setCellValue('T1', 'Plasma')
            ->setCellValue('U1', 'Plaquetas')
            ->setCellValue('V1', 'Crio precipitado')
            ->setCellValue('W1', 'Observação')
            ->setCellValue('X1', 'Data do cadastro')
            ->setCellValue('Y1', 'OLD_Procedimento proposto')
            ->setCellValue('Z1', 'OLD_Doença associada')
            ->setCellValue('AA1', 'OLD_Telefone')
            ->setCellValue('AB1', 'OLD_Diagnostico principal');
        

        $row_index_excel = 2;

        foreach($relatorio as $pedido)
        {
            $nascimento = new Carbon($pedido['dte_nascimento']);
            
            $planilha->setActiveSheetIndex(0)
            ->setCellValue('A'.$row_index_excel, $pedido['cod_pedido'])
            ->setCellValue('B'.$row_index_excel, $pedido['dte_pedido'])
            ->setCellValue('C'.$row_index_excel, $pedido['dsc_tipo_operacao'])
            ->setCellValue('D'.$row_index_excel, $pedido['dsc_encaminhamento'])
            ->setCellValue('E'.$row_index_excel, $pedido['num_prontuario'])
            ->setCellValue('F'.$row_index_excel, $pedido['txt_nome'])
            ->setCellValue('G'.$row_index_excel, $pedido['txt_nome_mae'])
            ->setCellValue('H'.$row_index_excel, $nascimento->toDateString())
            ->setCellValue('I'.$row_index_excel, $pedido['txt_sexo'])
            ->setCellValue('J'.$row_index_excel, $pedido['num_ddd_fone_residencial'].$pedido['num_fone_residencial'])
            ->setCellValue('K'.$row_index_excel, $pedido['num_ddd_fone_recado'].$pedido['txt_fone_recado'])
            ->setCellValue('L'.$row_index_excel, $pedido['num_cartao_saude'])
            ->setCellValue('M'.$row_index_excel, $pedido['txt_cidade'])
            ->setCellValue('N'.$row_index_excel, $pedido['txt_logradouro'])
            ->setCellValue('O'.$row_index_excel, $pedido['num_cep'])
            ->setCellValue('P'.$row_index_excel, $pedido['dsc_especialidade'])
            ->setCellValue('Q'.$row_index_excel, $pedido['dsc_sintomatologia'])
            ->setCellValue('R'.$row_index_excel, $pedido['dsc_doenca_maligna'])
            ->setCellValue('S'.$row_index_excel, $pedido['num_conc_hemacias'])
            ->setCellValue('T'.$row_index_excel, $pedido['num_plasma'])
            ->setCellValue('U'.$row_index_excel, $pedido['num_plaquetas'])
            ->setCellValue('V'.$row_index_excel, $pedido['num_crio_precipitado'])
            ->setCellValue('W'.$row_index_excel, $pedido['txt_observacoes'])
            ->setCellValue('X'.$row_index_excel, $pedido['dth_hora_registro'])
            ->setCellValue('Y'.$row_index_excel, $pedido['txt_procedimento_proposto'])
            ->setCellValue('Z'.$row_index_excel, $pedido['dsc_doenca_associada'])
            ->setCellValue('AA'.$row_index_excel, $pedido['num_ddd_telefone_atual'].$pedido['num_telefone_atual'])
            ->setCellValue('AB'.$row_index_excel, $pedido['txt_diagnostico_principal']);

            $row_index_excel++;
        }
        

        $planilha->getActiveSheet()->setTitle('Pedidos de hoje');

        $planilha->setActiveSheetIndex(0);

        $arquivo = 'pedidos-cirurgia_'.str_replace(' ', '_', $carbon->format('d m Y').'.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header("Content-Disposition: attachment;filename=\"".$arquivo."\"");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        header('Cache-Control: must-revalidate');
        header('Content-Transfer-Encoding: binary');
        header('Expires: ' . Carbon::now()->addDays(5)->toDateTimeString());
        header('Last-Modified: ' . Carbon::now()); // always modified
        header('Pragma: no-cache'); // HTTP/1.0

        unset($carbon);

        $writer = IOFactory::createWriter($planilha, 'Xlsx');
        $writer->save('php://output');

        exit;
    }
}
