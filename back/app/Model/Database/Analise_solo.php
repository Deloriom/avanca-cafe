<?php

namespace App\Model\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Analise_solo extends Model
{
        use SoftDeletes;

        protected $dates = [
            'created_at', 'updated_at', 'deleted_at'
        ];
        
        protected $fillable = [
            'id',
            'saturacao_solo',
            'tipo_calculo',
            'ctc',
            'magnesio',
            'calcio',
            'aluminio',
            'teor_argila',
            'teor_maximo_saturacao_aluminio',
            'talhao_id'
        ];
            
        protected $table = 'analise_solo';

        public static function scopeGet($query ,$col, $param, $id=0)
        {
            return $query->where($col,$param)->orWhere('id', $id);
        }
        
    }