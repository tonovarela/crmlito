<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Oportunidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Oportunidades', function (Blueprint $table) {
            $table->increments('idOportunidad');
            $table->integer('idCliente')->nullable();
            $table->string('nomOportunidad', 100);
            $table->integer('idEtapa')->nullable();
            $table->integer('idEstatus')->nullable();
            $table->integer('idTipo')->nullable();
            $table->integer('idExclusion')->nullable();
            $table->integer('idProbabilidad')->nullable();
            $table->integer('idAgente')->nullable();
            $table->string('Maquila', 2)->nullable();
            $table->float('Valor')->nullable();
            $table->dateTime('fecCreacion')->nullable();
            $table->dateTime('fecSolCotizacion')->nullable();
            $table->dateTime('fecListoCotizar')->nullable();
            $table->dateTime('fecCotizando')->nullable();
            $table->dateTime('fecRevision')->nullable();
            $table->dateTime('fecListoPresupuesto')->nullable();
            $table->dateTime('fecPresupTerminado')->nullable();
            $table->dateTime('fecResultadoF')->nullable();
            $table->dateTime('fecOrdenConcluida')->nullable();
            $table->dateTime('fecUltimoCambio')->nullable();
            $table->dateTime('fecCambioElemPre')->nullable();
            $table->integer('idUsuarioUltCambio')->nullable();
            $table->string('numOrdenMTC', 50)->nullable();
            $table->integer('idUsuarioCreacion')->nullable();
            $table->string('notaRetencion', 255)->nullable();
            $table->integer('idMotivoPerdida')->nullable();
            $table->dateTime('fecEsperaVobo')->nullable();
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
        Schema::dropIfExists('Oportunidades');
    }
}
