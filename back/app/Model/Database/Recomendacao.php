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
            'quantidade_calcario_ha_saturacao',
            'quantidade_calcario_teor_aluminio',
            'quantidade_calcario_ha_ca_mg',
            'calcario_id',
            'analise_solo_id'
        ];
            
        protected $table = 'recomendacao';

        public static function scopeGet($query ,$col, $param, $id=0)
        {
            return $query->where($col,$param)->orWhere('id', $id);
        }
        
    }
