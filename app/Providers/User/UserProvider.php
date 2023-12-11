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
        $this->app->bind(UserUpdateProvider::class, function ($app) {
            return new UserUpdateProvider($app);
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

    public static function findUserById(int $id): ?User
    {
        return User::where('id', $id)->first();
    }

    public static function verifyIfUserIsActive(User $user) : bool {
        if ($user->status === 'inactive') {
            return false;
        }
        return true;
    }
}
