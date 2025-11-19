<?php

namespace App\Policies;

use App\Models\Fornecedor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FornecedorPolicy
{
    /**
     * Permite que masters e admins façam qualquer coisa.
     */
    public function before(User $user, string $ability): bool|null
    {
        if (in_array($user->role, ['master', 'admin'])) {
            return true;
        }
        return null; // Deixa as outras regras decidirem
    }

    /**
     * Determine whether the user can view any models.
     * Qualquer usuário autenticado pode ver a página de índice.
     * O GlobalScope cuidará de filtrar os resultados.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Um usuário fornecedor só pode ver o seu próprio registro.
     */
    public function view(User $user, Fornecedor $fornecedor): bool
    {
        return $user->fornecedor_id === $fornecedor->id;
    }


    /**
     * Um usuário comum não pode criar fornecedores. (Só admin/master)
     */
    public function create(User $user): bool
    {
        return false; // A regra 'before' já libera para admin/master
    }

    /**
     * Um usuário fornecedor pode editar seu próprio registro, mas com restrições.
     */
    public function update(User $user, Fornecedor $fornecedor): bool
    {
        // 1ª Barreira: O usuário é o dono deste fornecedor? Se não, negue imediatamente.
        if ($user->fornecedor_id !== $fornecedor->id) {
            return false;
        }
        
        // 2ª Barreira: Se o usuário é um fornecedor, vamos verificar se ele está
        // tentando alterar campos que não deveria.
        if ($user->role === 'fornecedor') {
            // Lista de campos PROIBIDOS para o fornecedor.
            $forbiddenFields = [
                'cnpj',
                'ie',
                'status',
                'observacoes',
                'user_id'
            ];

            // O método request()->hasAny() verifica se a requisição contém QUALQUER um dos campos da lista.
            // Se ele estiver tentando enviar QUALQUER campo proibido, a permissão é negada.
            if (request()->hasAny($forbiddenFields)) {
                return false;
            }
        }
        return true; // Permitido se nenhum campo proibido foi alterado.
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fornecedor $fornecedor): bool
    {
        return false; // Apenas admin/master podem deletar (já tratado no before())
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fornecedor $fornecedor): bool
    {
        return false; // Apenas admin/master podem restaurar (já tratado no before())
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fornecedor $fornecedor): bool
    {
        return false; // Apenas admin/master podem forçar deleção (já tratado no before())
    }
}
