<?php

namespace App\Providers\Task;

class TaskUpdateProvider extends TaskProvider
{
    public function updateTask(array $data) : array
    {
        $task = $this->findTaskById($data['id']);
        $this->verifyifTaskBelongsToUser($task, $data['user_id']);
        $newData = $this->createUpdatedData($data);
        $task->update($newData);
        $return['data'] = $task->only(['id', 'description', 'status', 'deadline']);
        return $return;
    }

    private function createUpdatedData(array $data) : array
    {
        $newData['status'] = $data['status'];
        $newData['description'] = $data['description'];
        $newData['deadline'] = $data['deadline'] ?? null;
        return $newData;
    }
}
