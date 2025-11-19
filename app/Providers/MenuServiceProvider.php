<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Usamos um View Composer para injetar dados em uma view específica sempre que ela for renderizada.
        // Aqui, estamos dizendo: "Sempre que o layout 'layouts.app' for carregado..."
        View::composer('layouts.app', function ($view) {

            // 1. Defina a estrutura completa do menu
            $menuItems = [
                [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon'  => 'bi-house-door-fill', // Ícone do Bootstrap Icons
                    'roles' => ['master','admin', 'user'], // Visível para master, admin e user
                ],
                [
                    'title' => 'Usuários',
                    'route' => 'users.index',
                    'icon'  => 'bi-truck-front-fill',
                    'roles' => ['master','admin','fornecedor','cliente'], // Visível apenas para master e admin
                ],
                [
                    'title' => 'Fornecedores',
                    'route' => 'fornecedores.index',
                    'icon'  => 'bi-truck-front-fill',
                    'roles' => ['master','admin','fornecedor'], // Visível apenas para master e admin
                ],
                [
                    'title' => 'Produtos',
                    'route' => 'produtos.index',
                    'icon'  => 'bi-box-seam-fill',
                    'roles' => ['master','admin','fornecedor'], // Visível apenas para master e admin
                ],
                // Adicione aqui futuros itens de menu...
            ];

            // 2. Filtre os itens do menu com base no nível do usuário logado
            $filteredMenu = [];
            if (Auth::check()) {
                $userRole = Auth::user()->role;
                $filteredMenu = array_filter($menuItems, function ($item) use ($userRole) {
                    return in_array($userRole, $item['roles']);
                });
            }

            // 3. Compartilhe a variável '$menu' (já filtrada) com a view
            $view->with('menu', $filteredMenu);
        });
    }
}
