<?php

namespace App\Providers\Task;

class TaskDeleteProvider extends TaskProvider
{
    public function deleteTask(array $data) : array
    {
        $task = $this->findTaskById($data['id']);
        $this->verifyifTaskBelongsToUser($task, $data['user_id']);
        $task->delete();
        return ['message' => 'Task deleted successfully'];
    }
}
