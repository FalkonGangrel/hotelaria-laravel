<?php

namespace App\Models;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // Adicione a trait SoftDeletes
    use HasFactory, Notifiable, SoftDeletes,Authorizable;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_path', // Permitir atribuição em massa para o caminho da foto
        'role',       // Permitir atribuição em massa para o papel/função
    ];

    /**
     * Os atributos que devem ser escondidos nas serializações.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Esta é uma feature de segurança moderna do Laravel 10+.
        // Garante que qualquer valor passado para o campo 'password'
        // seja automaticamente criptografado usando o Hash do Laravel.
        'password' => 'hashed',
    ];
}