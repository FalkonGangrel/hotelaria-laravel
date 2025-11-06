<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'preco_venda',
        'quantidade_estoque',
        'fornecedor_id',
    ];

    /**
     * Define o relacionamento de que "Produto pertence a um Fornecedor".
     */
    public function fornecedor()
    {
        // 'belongsTo' significa "pertence a".
        // Estamos dizendo que um produto tem um fornecedor associado.
        return $this->belongsTo(Fornecedor::class);
    }
}
