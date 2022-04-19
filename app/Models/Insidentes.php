<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insidentes extends Model
{
    use SoftDeletes;
    protected $table = 'incidentes';
    protected $fillable	 = [
        'id',
        'criticidade_id',
        'tipo_id',
        'titulo',
        'descricao',
        'status'
    ];

    public function insidetesCriticidade()
    {
        return $this->hasOne('App\Models\Criticidades', 'id', 'criticidade_id');
    }

    public function insidetesTipos()
    {
        return $this->hasOne('App\Models\Tipos', 'id', 'tipo_id');
    }

}
