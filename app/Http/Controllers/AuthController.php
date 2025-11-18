<?php
namespace App\Http\Controllers;

use App\Core\AuthManager;
use App\Core\Controller;
use App\Core\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth.login');
    }

    public function login(Request $request): void
    {
        if (AuthManager::attempt($request->all())) {
            redirect('/dashboard');
        }
        flash('error', 'Credenciales inválidas');
        redirect('/login');
    }

    public function showRegister(): void
    {
        $this->view('auth.register');
    }

    public function register(Request $request): void
    {
        $user = new User($request->all());
        $user->password = password_hash($request->input('password'), PASSWORD_BCRYPT);
        $user->role = 'user';
        $user->plan = 'free';
        $user->save();
        AuthManager::login($user);
        redirect('/dashboard');
    }

    public function logout(): void
    {
        AuthManager::logout();
        redirect('/');
    }
}
