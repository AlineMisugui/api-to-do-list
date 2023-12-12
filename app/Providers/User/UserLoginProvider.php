<?php

namespace App\Providers\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

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
        $return['data'] = [
            'user' => $user->email,
            'token' => $token
        ];
        $return['status'] = 200;
        return $return;
    }

    public function forgotPassword(array $request): array
    {
        $user = $this->searchUserByEmail($request['email']);
        if (!$user) {
            throw new Exception('Unauthorized', 401);
        }
        $this->verifyUserEmail($user);
        $newPassword = $this->createNewPassword($user);
        $this->sendEmail($user, $newPassword);
        $return['data'] = ['message' => 'Email enviado com sucesso'];
        return $return;
    }

    private function createNewPassword(User $user): string
    {
        $newPassword = $this->generateRandomPassword();
        $user->update(['password' => bcrypt($newPassword)]);
        return $newPassword;
    }

    private function generateRandomPassword(): string
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
    }

    private function sendEmail(User $user, string $newPassword): void
    {
        Mail::send('emails.forgot-password', ['user' => $user, 'newPassword' => $newPassword], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Nova senha');
        });
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
