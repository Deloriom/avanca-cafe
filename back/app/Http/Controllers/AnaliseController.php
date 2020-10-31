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
        //     $this->validate($request, [
        //         'nome_proprietario' => 'required',
        //         'nome_propiedade' => 'required',
        //         //talhão
        //         // 'area_ha' => 'required',
        //         // 'espacamento_ruas' => 'required',
        //         // 'espacamento_plantas' => 'required',
        //         // 'prnt' => 'required',
        //         // analise solo
        //         'ph' => 'required',
        //         'p' => 'required',
        //         'ca' => 'required',
        //         'mg' => 'required',
        //         'k' => 'required'
        //     ]
        // );
        $data = $request->all();

        $this->criarPropietario($data);
        $this->salvarTodosDados($data);
        $this->calcularCalcarioPorHectare();

            return response()->json([
                'success' => true,
                'data' => $request->all()
            ]);


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
    
        // metodo por saturação de bases 1
        $resultadoSaturacaoBases = round(($talhao['saturacao_ideal'] - $analise['saturacao_solo']) * $analise['ctc']/76 ,2);

        if ($resultadoSaturacaoBases < 0) {
            $resultadoSaturacaoBases = $resultadoSaturacaoBases * -1;
        }
        
        // metodo 2 aluminio


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
        // se continuar esse calculo você chega no blend ideal de calcario

        $recomendacao = Recomendacao::updateOrCreate(
            ['analise_solo_id' => $this->analise_solo->id],
            [
                'quantidade_calcario_ha_saturacao' => $resultadoSaturacaoBases,
                // 'quantidade_calcario_teor_aluminio' => '',
                'quantidade_calcario_ha_ca_mg' => $resultadoCaMg,
                'calcario_id' => $calcarioEscolhido->id
            ]
        );
        dd($recomendacao);

    }

    private function salvarTodosDados($data) {
        $talhao = Talhao::updateOrCreate(
            ['proprietario_id' => $this->propietario->id],
            [
                'area_ha' => isset($data['area_ha'])?$data['area_ha']:null,
                'saturacao_ideal' => isset($data['saturacao_ideal'])?$data['saturacao_ideal']:null
            ]
        );
        $analise_solo = Analise_solo::updateOrCreate(
            ['talhao_id' => $talhao->id],
            [
                'saturacao_solo' => isset($data['saturacao_solo']) ? $data['saturacao_solo'] : null,
                'ctc' => isset($data['ctc']) ? $data['ctc'] : null,
                'magnesio' => isset($data['magnesio']) ? $data['magnesio'] : null,
                'calcio' => isset($data['calcio']) ? $data['calcio'] : null,
                'aluminio' => isset($data['aluminio']) ? $data['aluminio'] : null
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
                $propietarioModel->codigo = md5($data['nome_proprietario'] . time());
                $propietarioModel->save();
                $propietarioModel->refresh();
                $this->propietario = $propietarioModel;
            }
        } else {
            $propietarioModel->nome = $data['nome_proprietario'];
            $propietarioModel->codigo = md5($data['nome_proprietario'] . time());
            $propietarioModel->save();
            $propietarioModel->refresh();
            $this->propietario = $propietarioModel;
        }
    }

    
}
