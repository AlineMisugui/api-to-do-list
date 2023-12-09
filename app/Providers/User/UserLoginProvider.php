<?php

namespace App\Providers\User;

use App\Models\User;
use Carbon\Laravel\ServiceProvider;
use Exception;

class UserLoginProvider extends UserProvider
{
    public function authenticate(array $request): array
    {
        $user = $this->searchUserByEmail($request['email']);
        if (!$user) {
            throw new Exception('Unauthorized', 401);
        }
        $this->verifyUserEmail($user);
        $this->checkPassword($request['password'], $user);
        $token = $this->generateToken($user);
        return [
            'user' => $user->email,
            'token' => $token
        ];
    }

    private function checkPassword(string $password_given , User $user): void
    {
        $passwordIsValid = password_verify($password_given, $user->password);
        if (!$passwordIsValid) {
            throw new Exception('Unauthorized', 401);
        }
    }

    private function generateToken(User $user) {
        $token = $user->createToken('authToken')->plainTextToken;
        return $token;
    }
}
