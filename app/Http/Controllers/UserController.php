<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = UserResource::collection(User::all());
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Ingrese el nombre',
            'name.string' => 'El nombre debe ser texto',
            'name.max' => 'El nombre no puede tener mas de 255 caracteres',
            'email.required' => 'Ingrese el correo',
            'email.string' => 'El correo debe ser texto',
            'email.email' => 'Ingrese un correo valido',
            'email.max' => 'El correo no puede tener mas de 255 caracteres',
            'email.unique' => 'El correo ingresaddo ya existe',
            'password.required' => 'Ingrese la contraseña',
            'password.confirmed' => 'El password no coincide con el password de confirmacion',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect()
            ->route('users.index')
            ->with('message', "Se ha creado el usuario $user->name correctamente");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('Admin/Users/Show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'selectedRoles' => $user->getRoleNames(),
            'roles' => Role::all()->map(function ($role) {
                return $role->name;
            })
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255',
        ], [
            'name.required' => 'Ingrese el nombre',
            'name.string' => 'El nombre debe ser texto',
            'name.max' => 'El nombre no puede tener mas de 255 caracteres',
            'email.required' => 'Ingrese el correo',
            'email.string' => 'El correo debe ser texto',
            'email.email' => 'Ingrese un correo valido',
            'email.max' => 'El correo no puede tener mas de 255 caracteres',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->update();
        return redirect()
            ->route('users.index')
            ->with('message', "Se ha actualizado correctamente el usuario $user->name");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index');
    }
}
