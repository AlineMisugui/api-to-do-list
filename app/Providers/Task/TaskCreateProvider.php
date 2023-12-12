<?php

namespace App\Providers\Task;

use App\Models\Task;
use App\Providers\TaskGroup\TaskGroupProvider;
use App\Providers\User\UserProvider;
use Exception;
use Illuminate\Support\ServiceProvider;

class TaskCreateProvider extends ServiceProvider
{
    public function createTask(array $data): array
    {
        $user = UserProvider::findUserById($data['user_id']);
        $userIsActive = UserProvider::verifyIfUserIsActive($user);
        if (!$userIsActive) {
            throw new Exception('User is not active', 400);
        }
        $taskGroup = TaskGroupProvider::findTaskGroupById($data['group_id']);
        TaskGroupProvider::verifyIfGroupBelongsToUser($data['user_id'], $taskGroup->user_id);
        $newTask = Task::create($data);
        $newTask->setAttribute('group_name', $taskGroup->name);
        $return['data'] = $newTask->only(['id', 'description', 'group_name']);
        $return['status'] = 201;
        return $return;
    }
}
