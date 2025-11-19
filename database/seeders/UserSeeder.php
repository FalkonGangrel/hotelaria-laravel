<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fornecedor;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fornecedor = Fornecedor::first();

        // Senha padrão para todos os usuários de teste
        $defaultPassword = Hash::make('123!@#asdASD'); // Use uma senha segura!

        User::create([
            'name' => 'Usuário Master',
            'email' => 'master@app.com',
            'password' => $defaultPassword,
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Usuário Admin',
            'email' => 'admin@app.com',
            'password' => $defaultPassword,
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Usuário Comum',
            'email' => 'user@app.com',
            'password' => $defaultPassword,
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Usuário Cliente',
            'email' => 'cliente@app.com',
            'password' => $defaultPassword,
            'role' => 'cliente',
        ]);

        if ($fornecedor) {
            User::create([
                'name' => 'Usuário Fornecedor',
                'email' => 'fornecedor@app.com',
                'password' => $defaultPassword,
                'role' => 'fornecedor',
                'fornecedor_id' => $fornecedor->id,
            ]);
        }
    }
}
