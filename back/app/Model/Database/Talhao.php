<?php

namespace App\Model\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Talhao extends Model
{
        use SoftDeletes;

        protected $dates = [
            'created_at', 'updated_at', 'deleted_at'
        ];
        
        protected $fillable = [
            'id',
            'nome',
            'cultivar',
            'data_plantio',
            'area_ha',
            'espacamento_ruas',
            'espacamento_plantas',
            'prnt',
            'propriedade_id',
            //def null
            'previsao_colheitas_saca',   
            'saturacao_por_bases',
            'profundidade_corrigida',
            'superficie_cobertura'
        ];
            
        protected $table = 'talhao';

        public static function scopeGet($query ,$col, $param, $id=0)
        {
            return $query->where($col,$param)->orWhere('id', $id);
        }
        
    }
