<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Este método é executado ANTES de qualquer outro na policy.
     * É o lugar perfeito para nossa regra do 'super admin'.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Se o usuário logado for 'master', ele tem permissão para TUDO.
        // O 'return true' para a execução e concede o acesso imediatamente.
        if ($user->role === 'master') {
            return true;
        }

        // Se não for master, retorna null para que os outros métodos sejam verificados.
        return null;
    }

    /**
     * Determina se o usuário pode ver a lista de todos os usuários.
     */
    public function viewAny(User $user): bool
    {
        // Apenas 'master' (já tratado no before()) e 'admin' podem ver a lista.
        return $user->role === 'admin';
    }

    /**
     * Determina se o usuário pode criar novos usuários.
     */
    public function create(User $user): bool
    {
        // Apenas 'master' e 'admin' podem criar usuários.
        return $user->role === 'admin';
    }

    /**
     * Determina se o usuário logado ($user) pode atualizar um usuário alvo ($targetUser).
     */
    public function update(User $user, User $targetUser): bool
    {
        // Regra 1: 'admin' pode editar qualquer um, EXCETO um 'master'.
        if ($user->role === 'admin') {
            return $targetUser->role !== 'master';
        }

        // Regra 2: 'user', 'cliente' e 'fornecedor' só podem editar a si mesmos.
        if (in_array($user->role, ['user', 'cliente', 'fornecedor'])) {
            return $user->id === $targetUser->id;
        }

        // Nega todas as outras possibilidades por padrão.
        return false;
    }

    /**
     * Determina se o usuário pode desativar (deletar) um usuário alvo.
     */
    public function delete(User $user, User $targetUser): bool
    {
        // Um 'admin' não pode deletar um 'master'.
        if ($user->role === 'admin') {
            return $targetUser->role !== 'master';
        }
        
        // Ninguém mais (user, cliente, etc.) pode deletar outros.
        return false;
    }

    /**
     * Determina se o usuário pode restaurar um usuário alvo.
     * A lógica é a mesma do 'delete'.
     */
    public function restore(User $user, User $targetUser): bool
    {
        // Reutilizamos a lógica do método delete para não repetir código.
        return $this->delete($user, $targetUser);
    }

    /**
     * Determina se o usuário pode deletar permanentemente um usuário.
     */
    public function forceDelete(User $user, User $targetUser): bool
    {
        // Para segurança, vamos permitir isso apenas para o 'master' (já tratado no before).
        return false;
    }
}