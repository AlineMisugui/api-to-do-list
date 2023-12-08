<?php

namespace App\Providers\User;

use App\Models\User;

class UserRegisterProvider extends UserProvider
{
    public function registerUser(array $request)
    {
        $user = $this->createUser($request);
        $user->sendEmailVerificationNotification();
        return [
            'user' => $user->email
        ];
    }

    private function createUser(array $request)
    {
        $user = $this->createUserModel($request);
        $user->save();
        return $user;
    }

    private function createUserModel(array $request)
    {
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        return $user;
    }
}
