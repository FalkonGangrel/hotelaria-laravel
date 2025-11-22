<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ProdutoScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Se o usuário está logado E tem o role 'fornecedor'...
        if (Auth::check() && Auth::user()->role === 'fornecedor') {
            // ...aplique um filtro que só mostra produtos cujo fornecedor_id
            // é o mesmo ao qual o usuário está vinculado.
            $builder->where('fornecedor_id', Auth::user()->fornecedor_id);
        }
    }
}
