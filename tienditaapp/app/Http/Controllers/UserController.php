<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function create()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar el rol del usuario
        if ($user->rol !== 'administrador') {
            // Si el usuario no es administrador, redirigir con un mensaje de error
            return redirect()->route('productos.regreso')->with('error', 'Error no tienes permisos para esta opción consultalo con un administrador.');
        }

        // Si el usuario es administrador, mostrar la vista de crear usuario
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|in:administrador,empleado',
        ]);

        // Si la validación falla, redirigir de vuelta con los errores
        if ($validator->fails()) {
            return redirect()->back()
               ->withErrors($validator)
               ->withInput();
        }

        // Crear un nuevo usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Encriptar la contraseña
        $user->rol = $request->rol;
        $user->save();

        // Redirigir a una página de éxito, por ejemplo, la lista de usuarios
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
    }

    public function index()
    {
        // Obtener todos los usuarios
        $users = User::all();

        // Retornar la vista con la lista de usuarios
        return view('usuarios.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('usuarios.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol' => 'required|string|in:administrador,empleado',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->rol = $request->rol;
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }
    
}
