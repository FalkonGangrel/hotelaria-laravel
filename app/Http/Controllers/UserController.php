<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $sortBy = $request->query('sort_by', 'id'); // Padrão é ordenar por ID
        $sortDirection = $request->query('sort_direction', 'asc'); // Padrão é ascendente
        $perPage = $request->query('per_page', 10); // Padrão é 10 itens por página
        $filters = $request->only(['filter_nome']); // Pega apenas os filtros relevantes

        $users = User::withTrashed();

        if (!empty($filters['filter_nome'])) {
            $users->where('name', 'like', '%' . $filters['filter_nome'] . '%');
        }
        $users->orderBy($sortBy, $sortDirection);
        $users = $users->paginate($perPage);
        return view('users.index', [
            'users' => $users,
            'filters' => $filters,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:admin,user,cliente,fornecedor'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'photo' => ['nullable', 'image', 'max:7168'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $imageManager = new ImageManager(new Driver());
            $image = $request->file('photo');
            $imageProcessed = $imageManager->read($image)->cover(50, 70)->toJpg(80);
            $path = 'user_photos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($path, (string) $imageProcessed);
            $photoPath = $path;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['sometimes', 'required', 'in:admin,user,cliente,fornecedor'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'photo' => ['nullable', 'image', 'max:7168'],
        ]);

        if ($request->hasFile('photo')) {
            $imageManager = new ImageManager(new Driver());
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $image = $request->file('photo');
            $imageProcessed = $imageManager->read($image)->cover(50, 70)->toJpg(80);
            $path = 'user_photos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($path, (string) $imageProcessed);
            $validatedData['photo_path'] = $path;
        }
        
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }
        if (Auth::check() && !Auth::user()->can('create', User::class)) {
            unset($validatedData['role']);
        }
        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', ['user' => $user]);
    }
    
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário desativado com sucesso.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('restore', $user);
        $user->restore();
        return redirect()->route('users.index')->with('success', 'Usuário reativado com sucesso.');
    }
}