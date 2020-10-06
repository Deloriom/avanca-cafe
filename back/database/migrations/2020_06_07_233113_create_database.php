<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('proprietario', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('codigo', 32);
            $table->timestamps();
        });

        Schema::create('propriedade', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->unsignedBigInteger('proprietario_id');
            $table->foreign('proprietario_id')->references('id')->on('proprietario');
            $table->timestamps();
        });

        Schema::create('talhao', function (Blueprint $table) {
            $table->id();
            $table->string('cultivar', 50);
            $table->date('data_plantio');
            $table->decimal('area_ha', 10,2);
            $table->decimal('espacamento_ruas', 10,2);
            $table->decimal('espacamento_plantas', 10,2);
            $table->decimal('previsao_colheitas_saca', 10,2)->default(null);
            $table->decimal('saturacao_por_bases', 10,2)->default(null);
            $table->decimal('prnt', 10,2);
            $table->decimal('profundidade_corrigida', 10,2)->default(null);
            $table->decimal('superficie_cobertura', 10,2)->default(null);

            $table->unsignedBigInteger('propriedade_id');
            $table->foreign('propriedade_id')->references('id')->on('propriedade');
            $table->timestamps();
        });

        Schema::create('analise_solo', function (Blueprint $table) {
            $table->id();
            $table->decimal('ph', 10, 2);
            $table->decimal('p', 10, 2);
            $table->decimal('ca', 10, 2);
            $table->decimal('mg', 10, 2);
            $table->decimal('k', 10, 2);
            $table->decimal('so', 10, 2)->default(null);
            $table->decimal('b', 10, 2)->default(null);
            $table->decimal('zn', 10, 2)->default(null);
            $table->decimal('cu', 10, 2)->default(null);
            $table->decimal('mn', 10, 2)->default(null);
            $table->decimal('fe', 10, 2)->default(null);
            $table->decimal('ai', 10, 2)->default(null);
            $table->decimal('t_mi', 10, 2)->default(null);
            $table->decimal('h_ai', 10, 2)->default(null);
            $table->decimal('m', 10, 2)->default(null);
            $table->decimal('sb', 10, 2)->default(null);
            $table->decimal('t_ma', 10, 2)->default(null);
            $table->decimal('v', 10, 2)->default(null);
            $table->decimal('ca_ctc', 10, 2)->default(null);
            $table->decimal('mg_ctc', 10, 2)->default(null);
            $table->decimal('k_ctc', 10, 2)->default(null);
            $table->decimal('ca_mg', 10, 2)->default(null);
            $table->decimal('materia_organica', 10, 2)->default(null);
            $table->decimal('teor_argila', 10, 2)->default(null);

            $table->unsignedBigInteger('talhao_id');
            $table->foreign('talhao_id')->references('id')->on('talhao');
            $table->timestamps();
        });

        Schema::create('recomendacao', function (Blueprint $table) {
            $table->id();
            $table->date('data_recomendacao');
            $table->char('safra_ano', 9);
            $table->decimal('quantidade_calcario_ha', 10, 2);
            $table->decimal('gramas_metro_linear', 10, 2);
            $table->char('tipo_calcario', 20);
            $table->decimal('prnt', 10, 2);
            $table->decimal('quantidade_calcario_talhao', 10, 2);
            $table->decimal('nitrogenio', 10, 2);
            $table->decimal('fosforo', 10, 2);
            $table->decimal('potassio', 10, 2);
            $table->char('fonte', 8);
            $table->decimal('kg_ha', 10, 2);
            $table->decimal('sc_talhao', 10, 2);
            $table->decimal('gramas_planta', 10, 2);
            $table->decimal('gramas_metro', 10, 2);
            $table->unsignedBigInteger('analise_solo_id');
            $table->foreign('analise_solo_id')->references('id')->on('analise_solo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recomendacao');
        Schema::dropIfExists('analise_solo');
        Schema::dropIfExists('talhao');
        Schema::dropIfExists('propriedade');
        Schema::dropIfExists('proprietario');
    }
}
