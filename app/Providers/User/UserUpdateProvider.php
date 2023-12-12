<?php

namespace App\Providers\User;

use Exception;

class UserUpdateProvider extends UserProvider
{
    public function update(array $data) : array {
        $this->app->request->user()->update($data);
        $updated_user = $this->app->request->user()->refresh();
        $return['data'] = [
            'user' => $updated_user->only(['id', 'name', 'email', 'status']),
            'message' => 'Usuário atualizado com sucesso'
        ];
        return $return;
    }

    public function changePassword(array $data) : array {
        $user = $this->app->request->user();
        if (!password_verify($data['current_password'], $user->password)) {
            throw new Exception('Senha atual incorreta', 401);
        }
        $user->update(['password' => bcrypt($data['new_password'])]);
        $return['data'] = ['message' => 'Senha alterada com sucesso'];
        return $return;
    }
}
