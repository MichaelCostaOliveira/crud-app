<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criticidades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('tipos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('incidentes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->longText('descricao')->nullable();
            $table->boolean('status');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('incidentes', function (Blueprint $table) {
            $table->integer('criticidade_id')->unsigned()->nullable()->after('id');
            $table->foreign('criticidade_id')->references('id')->on('criticidades');
        });
        Schema::table('incidentes', function (Blueprint $table) {
            $table->integer('tipo_id')->unsigned()->nullable()->after('id');
            $table->foreign('tipo_id')->references('id')->on('tipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
