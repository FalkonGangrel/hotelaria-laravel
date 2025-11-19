<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // Importar

class Categoria extends Model
{
    use HasFactory, SoftDeletes; // Usar

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'slug',
        'parent_id',
    ];

    /**
     * Define o relacionamento: Uma Categoria TEM MUITOS Produtos.
     */
    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'categoria_produto');
    }

    /**
     * Define o auto-relacionamento: Uma Categoria PERTENCE A UMA Categoria-Pai.
     */
    public function parent(): BelongsTo
    {
        // Precisamos especificar a chave estrangeira 'parent_id' porque não segue a convenção.
        return $this->belongsTo(Categoria::class, 'parent_id');
    }

    /**
     * Define o auto-relacionamento: Uma Categoria TEM MUITAS Categorias-Filhas.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Categoria::class, 'parent_id');
    }
}