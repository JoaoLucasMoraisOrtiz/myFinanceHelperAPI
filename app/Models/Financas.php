<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financas extends Model
{
    protected $table = 'financas';
    
    protected $fillable = [
        'user_id',
        'data_json'
    ];

    protected $casts = [
        'data_json' => 'array'
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obter dados financeiros de um usuário
     */
    public static function getDadosUsuario($userId)
    {
        $financas = self::where('user_id', $userId)->first();
        
        if (!$financas) {
            return [
                'receitas' => [],
                'despesas' => [],
                'planejamento' => [
                    'receitas' => [],
                    'despesas' => []
                ]
            ];
        }

        return $financas->data_json;
    }

    /**
     * Salvar dados financeiros de um usuário
     */
    public static function salvarDadosUsuario($userId, $dados)
    {
        return self::updateOrCreate(
            ['user_id' => $userId],
            ['data_json' => $dados]
        );
    }
}
