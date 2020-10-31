<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('talhao', function (Blueprint $table) {
            $table->id();
            $table->decimal('area_ha', 10,2);
            $table->decimal('saturacao_ideal', 10,2);

            $table->unsignedBigInteger('proprietario_id');
            $table->foreign('proprietario_id')->references('id')->on('proprietario');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('analise_solo', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo_calculo');
            $table->decimal('saturacao_solo', 10,2);
            $table->decimal('ctc', 10,2);

            $table->decimal('magnesio', 10,2);
            $table->decimal('calcio', 10,2);
            $table->decimal('aluminio', 10,2);
            $table->decimal('teor_argila', 10,2);
            $table->decimal('teor_maximo_saturacao_aluminio', 10,2);

            $table->unsignedBigInteger('talhao_id');
            $table->foreign('talhao_id')->references('id')->on('talhao');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('calcario', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50);
            $table->string('tipo', 50);
            $table->decimal('pn', 10, 2); // Poder de neutralização
            $table->decimal('prnt', 10, 2); // poder geral de neutralização
            $table->decimal('ca', 10, 2); // porcentagem de calcio
            $table->decimal('mg', 10, 2); // porcentagem de magnesio
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('recomendacao', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantidade_calcario_ha_saturacao', 10, 2)->nullable(); // quantidade_calcario por hectare no metodo saturaçao
            $table->decimal('quantidade_calcario_teor_aluminio', 10, 2)->nullable(); // quantidade_calcario por hectare no metodo saturaçao
            $table->decimal('quantidade_calcario_ha_ca_mg', 10, 2)->nullable(); // quantidade_calcario por hectare no metodo ca_mg
            $table->unsignedBigInteger('calcario_id')->nullable(); // calcario escolhido para suprir a nescessidade ca/mg
            $table->unsignedBigInteger('analise_solo_id');
            $table->foreign('analise_solo_id')->references('id')->on('analise_solo');
            $table->foreign('calcario_id')->references('id')->on('calcario');
            $table->softDeletes();
            $table->timestamps();
        });

        
        DB::table('calcario')->insert(
            [ 
                'id'=> 1,
                'nome' => 'Calcario paraiso',
                'tipo' => 'dolomitico',
                'pn' => 77,
                'prnt' => 76,
                'ca' => 23,
                'mg' => 17
            ]
        );
        DB::table('calcario')->insert(
            [ 
                'id'=> 2,
                'nome' => 'Calcario minasol',
                'tipo' => 'calcitico',
                'pn' => 77,
                'prnt' => 92,
                'ca' => 50,
                'mg' => 3
            ]
        );
        DB::table('calcario')->insert(
            [ 
                'id'=> 3,
                'nome' => 'Calcario Solo fértil',
                'tipo' => 'magnesiano',
                'pn' => 77,
                'prnt' => 85,
                'ca' => 40,
                'mg' => 10
            ]
        );

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
