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
            'ph',
            'p',
            'ca',
            'mg',
            'k',
            'talhao_id',
            // def null
            'so',
            'b',
            'zn',
            'cu',
            'mn',
            'fe',
            'ai',
            't_mi',
            'h_ai',
            'm',
            'sb',
            't_ma',
            'v',
            'ca_ctc',
            'mg_ctc',
            'k_ctc',
            'ca_mg',
            'materia_organica',
            'teor_argila'
        ];
            
        protected $table = 'analise_solo';

        public static function scopeGet($query ,$col, $param, $id=0)
        {
            return $query->where($col,$param)->orWhere('id', $id);
        }
        
    }