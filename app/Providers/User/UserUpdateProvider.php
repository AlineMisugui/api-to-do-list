<?php

namespace App\Providers\User;

class UserUpdateProvider extends UserProvider
{
    public function update(array $data) : array {
        $this->app->request->user()->update($data);
        $updated_user = $this->app->request->user()->refresh();
        return [
            'user' => $updated_user->only(['id', 'name', 'email', 'status']),
            'message' => 'Usuário atualizado com sucesso'
        ];
    }
}
