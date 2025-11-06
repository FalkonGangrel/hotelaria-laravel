<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

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
