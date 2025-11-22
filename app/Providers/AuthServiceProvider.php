<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\User;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Policies\UserPolicy;
use App\Policies\FornecedorPolicy;
use App\Policies\ProdutoPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        User::class => UserPolicy::class,
        Fornecedor::class => FornecedorPolicy::class,
        Produto::class => ProdutoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
