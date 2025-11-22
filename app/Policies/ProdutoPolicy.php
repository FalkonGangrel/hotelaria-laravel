<?php

namespace App\Policies;

use App\Models\Produto;
use App\Models\User;

class ProdutoPolicy
{
    /**
     * Permite que masters e admins façam qualquer coisa.
     */
    public function before(User $user, string $ability): bool|null
    {
        if (in_array($user->role, ['master', 'admin'])) {
            return true;
        }
        return null;
    }

    /**
     * Qualquer usuário autenticado pode tentar ver a página de listagem.
     * O Global Scope cuidará de filtrar os resultados para o 'fornecedor'.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Um usuário fornecedor só pode ver um produto que pertença ao seu fornecedor.
     */
    public function view(User $user, Produto $produto): bool
    {
        return $user->fornecedor_id === $produto->fornecedor_id;
    }

    /**
     * REGRA DE NEGÓCIO: Apenas admin/master podem criar produtos.
     */
    public function create(User $user): bool
    {
        return false; // 'before()' já libera para admin/master.
    }

    /**
     * Um usuário fornecedor só pode atualizar um produto que pertença ao seu fornecedor.
     */
    public function update(User $user, Produto $produto): bool
    {
        // A validação de *quais campos* ele pode mudar será feita no Form Request,
        // mas a permissão básica de "pode tocar neste produto?" é definida aqui.
        return $user->fornecedor_id === $produto->fornecedor_id;
    }

    /**
     * REGRA DE NEGÓCIO: Apenas admin/master podem deletar produtos.
     */
    public function delete(User $user, Produto $produto): bool
    {
        return false;
    }

    /**
     * REGRA DE NEGÓCIO: Apenas admin/master podem restaurar produtos.
     */
    public function restore(User $user, Produto $produto): bool
    {
        return false;
    }
}