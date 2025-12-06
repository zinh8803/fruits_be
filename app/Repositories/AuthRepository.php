<?php
namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function registerUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->user->create($data);
    }
    
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