<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\FornecedorScope;

class Fornecedor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'fornecedores';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'ie',
        'email',
        'email2',
        'telefone',
        'telefone2',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'status',
        'observacoes',
        'user_id',
    ];

    /**
     * O "boot" do model. Aqui definimos os gatilhos automáticos.
     */
    protected static function booted(): void
    {

        // Aplicar o nosso filtro de segurança global
        static::addGlobalScope(new FornecedorScope);

        // Antes de deletar (desativar), execute esta função.
        static::deleting(function (Fornecedor $fornecedor) {
            // Se o status ainda não for 'inativo', mude para 'inativo'.
            if ($fornecedor->status !== 'inativo') {
                $fornecedor->status = 'inativo';
                $fornecedor->save(); // Salva a alteração antes de aplicar o soft delete.
            }
        });

        // Antes de restaurar (reativar), execute esta função.
        static::restoring(function (Fornecedor $fornecedor) {
            // Ao restaurar, o status sempre volta para "Em Análise".
            $fornecedor->status = 'em_analise';
        });
    }

    /**
     * Define a relação de pertencimento a um Usuário.
     * Um fornecedor pertence a uma conta de usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define o relacionamento: Um Fornecedor TEM MUITOS Produtos.
     */
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }
}