<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "username" => "required|string",
            "password" => "required|string",
        ]);

        $user = User::where("Username", $credentials["username"])->first();

        if (!$user) {
            return back()->withErrors(["username" => "Usuario no encontrado."])->withInput();
        }

        // Cuenta especial de Admin de desarrollo (admin / admin123)
        $isDevelopmentAdmin = ($credentials['username'] === 'admin' && $credentials['password'] === 'admin123');

        // Normalizar el prefijo de BCrypt de C# ($2a$ -> $2y$) para evitar que Laravel lance excepciones fatales
        $normalHash = str_replace('$2a$', '$2y$', $user->PasswordHash);

        if ($isDevelopmentAdmin || Hash::check($credentials["password"], $normalHash)) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended("/admin");
            }
            return redirect()->intended("/");
        }

        return back()->withErrors(["password" => "Contraseña incorrecta."])->withInput();
    }

    public function showRegister()
    {
        return view("auth.register");
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            "username" => "required|string|unique:usuarios,Username|max:255",
            "password" => "required|string|min:4",
        ]);

        $hashedPassword = Hash::make($data["password"]);

        $user = User::create([
            "Username" => trim($data["username"]),
            "PasswordHash" => $hashedPassword,
            "RolId" => 2, // Cliente
        ]);

        Auth::login($user);
        return redirect("/")->with("msg", "Usuario registrado con éxito.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/");
    }
}