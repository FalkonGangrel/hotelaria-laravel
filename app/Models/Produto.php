<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes; // Importar SoftDeletes

class Produto extends Model
{
    use HasFactory, SoftDeletes; // Usar SoftDeletes

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'sku',
        'barcode',
        'descricao',
        'unidade_medida',
        'preco_custo',
        'preco_venda',
        'quantidade_estoque',
        'estoque_minimo',
        'status',
        'fornecedor_id',
    ];

    /**
     * Define o relacionamento: Um Produto PERTENCE A UM Fornecedor.
     * (A relação inversa de Fornecedor->produtos)
     */
    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    /**
     * Define o relacionamento: Um Produto PERTENCE A MUITAS Categorias.
     */
    public function categorias(): BelongsToMany
    {
        // O Laravel entende a tabela pivot 'categoria_produto' por convenção.
        return $this->belongsToMany(Categoria::class, 'categoria_produto');
    }
}