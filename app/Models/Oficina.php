<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    protected $table = '_oficina';

    protected $primaryKey = 'id';

    protected $fillable = ['nome', 'descricao', 'carga_horaria', 'id_instrutor', 'data_inicio', 'data_fim', 'horario', 'local', 'vagas'];

    public function instrutor()
    {
        return $this->belongsTo(Instrutores::class, 'id_instrutor');
    }

    public function inscricoes()
    {
        return $this->hasMany(InscricoesOficina::class, 'id_oficina');
    }
}
