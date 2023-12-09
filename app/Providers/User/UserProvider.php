<?php

namespace App\Providers\User;

use App\Models\User;
use Exception;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
    public function register() {
        $this->app->bind(UserLoginProvider::class, function ($app) {
            return new UserLoginProvider($app);
        });
        $this->app->bind(UserRegisterProvider::class, function ($app) {
            return new UserRegisterProvider($app);
        });
    }
    public function searchUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
    public function verifyUserEmail(User $user): void
    {
        if (!$user->email_verified_at) {
            throw new Exception('Email não verificado', 401);
        }
    }
}
