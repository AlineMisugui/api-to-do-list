<?php

namespace App\Providers\User;

use App\Models\User;
use Carbon\Laravel\ServiceProvider;
use Exception;

class UserLoginProvider extends ServiceProvider
{
    public function authenticate(array $request): array
    {
        $user = $this->verifyUser($request);
        $token = $this->generateToken($user);
        return [
            'user' => $user->email,
            'token' => $token
        ];
    }

    private function verifyUser(array $request): User
    {
        $user = User::where('email', $request['email'])->where('password', $request['password'])->first();
        if (!$user) {
            throw new Exception('Unauthorized', 401);
        }
        return $user;
    }

    private function generateToken(User $user) {
        $token = $user->createToken('authToken')->accessToken;
        return $token;
    }
}
