<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Database\Analise_solo;
use App\Model\Database\Calcario;
use App\Model\Database\Proprietario;
use App\Model\Database\Recomendacao;
use App\Model\Database\Talhao;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class AnaliseController extends Controller
{

    private $propietario;
    private $talhao;
    private $analise_solo;
    private $recomendacao;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

 

    public function analise(Request $request)
    {
        $analiseModel = new Analise_solo();
        try {
        $data = $request->all();

        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }
        

        $this->criarPropietario($data);
        $this->salvarTodosDados($data);
        $this->calcularCalcarioPorHectare();
        
        $analise = $this->analise_solo;
        $recomendacao = $this->recomendacao;
        $talhao = $this->talhao;

        switch ($this->analise_solo['tipo_calculo']) {
        case 1:
            $dados = [];
            $dados['area'] = $talhao['area_ha'];
            $dados['toneladas_ha'] = $recomendacao['quantidade_calcario_ha_saturacao'];
            $dados['total_toneladas'] = round(($recomendacao['quantidade_calcario_ha_saturacao']) * $talhao['area_ha'],2);
            $dados['total_kilos'] = round(($recomendacao['quantidade_calcario_ha_saturacao'] * 1000) * $talhao['area_ha'],2);
            $calcario = DB::table('calcario')->select('*')->where('id', '=', $recomendacao['calcario_id'])->first();
            $dados['nome_calcario'] = $calcario->nome;
            $dados['tipo_calcario'] = $calcario->tipo;
            $dados['Calculo utilizado'] = $analise->tipo_calculo;
            $dados['Calculo utilizado'] = $analise->tipo_calculo;

            return response()->json([
                'success' => true,
                'data' => $dados
            ]);
            break;
        case 2:
            $dados = [];
            $dados['area'] = $talhao['area_ha'];
            $dados['toneladas_ha'] = $recomendacao['quantidade_calcario_teor_aluminio'];
            $dados['total_toneladas'] = round(($recomendacao['quantidade_calcario_teor_aluminio']) * $talhao['area_ha'],2);
            $dados['total_kilos'] = round(($recomendacao['quantidade_calcario_teor_aluminio'] * 1000) * $talhao['area_ha'],2);
            $calcario = DB::table('calcario')->select('*')->where('id', '=', $recomendacao['calcario_id'])->first();
            $dados['nome_calcario'] = $calcario->nome;
            $dados['tipo_calcario'] = $calcario->tipo;
            $dados['Calculo utilizado'] = $analise->tipo_calculo;

            return response()->json([
                'success' => true,
                'data' => $dados
            ]);
            break;
        case 3:
            $dados = [];
            $dados['area'] = $talhao['area_ha'];
            $dados['toneladas_ha'] = $recomendacao['quantidade_calcario_ha_ca_mg'];
            $dados['total_toneladas'] = round(($recomendacao['quantidade_calcario_ha_ca_mg']) * $talhao['area_ha'],2);
            $dados['total_kilos'] = round(($recomendacao['quantidade_calcario_ha_ca_mg'] * 1000) * $talhao['area_ha'],2);
            $calcario = DB::table('calcario')->select('*')->where('id', '=', $recomendacao['calcario_id'])->first();
            $dados['nome_calcario'] = $calcario->nome;
            $dados['tipo_calcario'] = $calcario->tipo;
            $dados['Calculo utilizado'] = $analise->tipo_calculo;

            return response()->json([
                'success' => true,
                'data' => $dados
            ]);
            break;
        }


        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $e->getMessage()
            ]);
        }
    }

    private function calcularCalcarioPorHectare() {
        $analise = $this->analise_solo;
        $talhao = $this->talhao;
        
        switch ($analise['tipo_calculo']) {
            case 1:
                // metodo por saturação de bases 1
                $resultadoSaturacaoBases = round(($talhao['saturacao_ideal'] - $analise['saturacao_solo']) * $analise['ctc']/76 ,2);

                if ($resultadoSaturacaoBases < 0) {
                    $resultadoSaturacaoBases = $resultadoSaturacaoBases * -1;
                }
                $calcarioEscolhido = 1;
                break;
            case 2:
                // metodo 2 aluminio
                $tampao_solo = 0;
                $argila = $analise['teor_argila'];
                if ($argila <= 15) {
                    $tampao_solo = ($argila * 1)/15;
                } else if ($argila <= 35) {
                    $tampao_solo = ($argila * 1)/15;
                } else if ($argila <= 60) {
                    $tampao_solo = ($argila * 2)/35;
                } else if ($argila > 60) {
                    $tampao_solo = ($argila * 3)/60;
                }
                $tampao_solo = round($tampao_solo,2);
                if($tampao_solo > 4) {
                    $tampao_solo = 4.0;
                }

                $resultadoTeorAluminio = round($tampao_solo * 
                ($analise['teor_maximo_saturacao_aluminio'] * $analise['ctc']/100) +
                (3 - ($analise['calcio'] + $analise['magnesio'])), 2);
                $calcarioEscolhido = 1;
                break;
            case 3:
                // metodo 3 ca/mg
                $fatorConversaoMagnesio = 0.404;
                $faixaIdealMagnesio = 0.17;
                $resultadoMagnesio = round((($analise['ctc'] * $faixaIdealMagnesio) - $analise['magnesio']) * $fatorConversaoMagnesio,2);
                
                $fatorConversaoCalcio = 0.56;
                $faixaIdealCalcio = 0.5;
                $resultadoCalcio = round((($analise['ctc'] * $faixaIdealCalcio) - $analise['calcio']) * $fatorConversaoCalcio,2);

                $relacaoCalcioMagnesio = $resultadoCalcio/$resultadoMagnesio;
                $calcarioEscolhido = null;
                $calcario = DB::table('calcario')->select('*')->get();

                foreach ($calcario as $key => $value) {
                    $relacaoCalcioMagnesioCalcario = round($value->ca/$value->mg,2);
                    $value->relacao = $relacaoCalcioMagnesioCalcario;
                    if (!isset($calcarioEscolhido)) {
                        $calcarioEscolhido = $value;
                    } else {
                        if (abs($relacaoCalcioMagnesio - $relacaoCalcioMagnesioCalcario) < abs($relacaoCalcioMagnesio -  $calcarioEscolhido->relacao)) {
                            $calcarioEscolhido = $value;
                        }
                    }
                }
                // verifica se o magnesio é abaixo de meio centimol
                if($resultadoMagnesio < 0.5) {
                    $resultadoCaMg = round($resultadoMagnesio / ($calcarioEscolhido->mg/100),2);
                } else {
                    $resultadoCaMg = round($resultadoCalcio / ($calcarioEscolhido->ca/100),2);
                }

                if ($resultadoCaMg < 0) {
                    $resultadoCaMg = $resultadoCaMg * -1;
                }
                $calcarioEscolhido = $calcarioEscolhido->id;
                break;
        }

        $recomendacao = Recomendacao::updateOrCreate(
            ['analise_solo_id' => $this->analise_solo->id],
            [
                'quantidade_calcario_ha_saturacao' => isset($resultadoSaturacaoBases)?$resultadoSaturacaoBases: null,
                'quantidade_calcario_teor_aluminio' => isset($resultadoTeorAluminio)? $resultadoTeorAluminio: null,
                'quantidade_calcario_ha_ca_mg' => isset($resultadoCaMg)? $resultadoCaMg: null,
                'calcario_id' => $calcarioEscolhido
            ]
        );

        $this->recomendacao = $recomendacao;
    }

    private function salvarTodosDados($data) {
        $talhao = Talhao::updateOrCreate(
            ['proprietario_id' => $this->propietario->id],
            [
                'area_ha' => isset($data['area_ha'])?$data['area_ha']:null,
                'saturacao_ideal' => isset($data['saturacao_ideal'])?$data['saturacao_ideal']: 0
            ]
        );
        $analise_solo = Analise_solo::updateOrCreate(
            ['talhao_id' => $talhao->id],
            [
                'tipo_calculo' => $data['tipo_calculo'],
                'saturacao_solo' => isset($data['saturacao_solo']) ? $data['saturacao_solo'] : 0,
                'ctc' => isset($data['ctc']) ? $data['ctc'] : 0,
                'magnesio' => isset($data['magnesio']) ? $data['magnesio'] : 0,
                'calcio' => isset($data['calcio']) ? $data['calcio'] : 0,
                'aluminio' => isset($data['aluminio']) ? $data['aluminio'] : 0,
                'teor_argila' => isset($data['teor_argila']) ? $data['teor_argila'] : 0,
                'teor_maximo_saturacao_aluminio' => isset($data['teor_maximo_saturacao_aluminio']) ? $data['teor_maximo_saturacao_aluminio'] : 0
            ]
        );
        $this->talhao = $talhao;
        $this->analise_solo = $analise_solo;
    }

    private function criarPropietario($data){
        $propietarioModel = new Proprietario();
        if(isset($data['id_propietario'])) {
            $propietario = $propietarioModel->find($data['id_propietario']);
            if($propietario) {
                return $propietario;
            } else {
                $propietarioModel->nome = $data['nome_proprietario'];
                $propietarioModel->save();
                $propietarioModel->refresh();
                $this->propietario = $propietarioModel;
            }
        } else {
            $propietarioModel->nome = $data['nome_proprietario'];
            $propietarioModel->save();
            $propietarioModel->refresh();
            $this->propietario = $propietarioModel;
        }
    }

    
}
