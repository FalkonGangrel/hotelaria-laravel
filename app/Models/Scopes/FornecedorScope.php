<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class FornecedorScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Se o usuário está logado E tem o role 'fornecedor'...
        if (Auth::check() && Auth::user()->role === 'fornecedor') {
            // ...aplique um filtro que só permite ver o fornecedor ao qual ele está vinculado.
            $builder->where('id', Auth::user()->fornecedor_id);
        }
        // Se for master ou admin, nenhum filtro é aplicado, então eles veem tudo.
    }
}
