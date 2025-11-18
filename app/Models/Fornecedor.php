<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * Define a relação de pertencimento a um Usuário.
     * Um fornecedor pertence a uma conta de usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}