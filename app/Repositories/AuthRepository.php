<?php
namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function attemptLogin($email, $password)
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }
        return Auth::user();
    }

    public function logout($user)
    {
        Auth::logout();
        return true;
    }
}