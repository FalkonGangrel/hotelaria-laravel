<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * O nome da tabela associada com o model.
     *
     * @var string
     */
    protected $table = 'fornecedores';

    protected $fillable = [
        'nome',
        'cnpj',
        'email',
    ];
    
    /**
     * Define o relacionamento de que "Fornecedor possui muitos Produtos".
     */
    public function produtos()
    {
        // 'hasMany' significa "tem muitos".
        // Estamos dizendo que um fornecedor pode ter vários produtos.
        return $this->hasMany(Produto::class);
    }
}
