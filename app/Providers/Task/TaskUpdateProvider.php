<?php

namespace App\Providers\Task;

class TaskUpdateProvider extends TaskProvider
{
    public function updateTask(array $data) : array
    {
        $task = $this->findTaskById($data['id']);
        $this->verifyifTaskBelongsToUser($task, $data['user_id']);
        $task->update($data);
        $return = $task->only(['id', 'description', 'status', 'deadline']);
        return $return;
    }
}
