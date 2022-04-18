<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Spa extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos')->insert([
            ['nome' => 'Alarme'],
            ['nome' => 'Incidente'],
            ['nome' => 'Outros']
        ]);

        DB::table('criticidades')->insert([
            ['nome' => 'alta'],
            ['nome' => 'média'],
            ['nome' => 'baixa']
        ]);

        DB::table('incidentes')->insert([
            'criticidade_id' => 1,
            'tipo_id' => 1,
            'titulo' => 'teste',
            'descricao' => 'descrição teste',
            'status' => 1
        ]);
    }
}
