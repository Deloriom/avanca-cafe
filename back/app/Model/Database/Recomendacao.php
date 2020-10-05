<?php

namespace App\Model\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recomendacao extends Model
{
        use SoftDeletes;

        protected $dates = [
            'created_at', 'updated_at', 'deleted_at'
        ];
        
        protected $fillable = [
            'id',
            'data_recomendacao',
            'safra_ano',
            'quantidade_calcario_ha',
            'gramas_metro_linear',
            'tipo_calcario',
            'prnt',
            'quantidade_calcario_talhao',
            'nitrogenio',
            'fosforo',
            'potassio',
            'fonte',
            'kg_ha',
            'sc_talhao',
            'gramas_planta',
            'gramas_metro',
            'analise_solo_id'
        ];
            
        protected $table = 'recomendacao';

        public static function scopeGet($query ,$col, $param, $id=0)
        {
            return $query->where($col,$param)->orWhere('id', $id);
        }
        
    }
