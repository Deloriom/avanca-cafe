<?php

namespace App\Model\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Propietario extends Model
{
        use SoftDeletes;

        protected $dates = [
            'created_at', 'updated_at', 'deleted_at'
        ];
        
        protected $fillable = [
            'id',
            'nome',
            'login',
            'senha'
        ];
        protected $table = 'proprietario';

        public static function scopeGet($query ,$col, $param, $id=0)
        {
            return $query->where($col,$param)->orWhere('id', $id);
        }

        public function savePropietario($dados) {
            $this->fill($dados);
            return $this->save();
        }

        public function getUsuarioLoginSenha($login, $senha) {
            $query = DB::table('proprietario as pr');
            $query->select('*');
            $query->where('login', '=', $login);
            $query->where('senha', '=', $senha);
            return $query->first();
        }

        public static function scopegetPropietario($query ,$col, $param, $id=0)
        {
            return $query->where($col,$param)->orWhere('id', $id);
        }
}

