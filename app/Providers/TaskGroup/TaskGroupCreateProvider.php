<?php

namespace App\Providers\TaskGroup;

use App\Models\TaskGroup;

class TaskGroupCreateProvider extends TaskGroupProvider
{
    public function createTaskGroup(array $data) : array {
        $taskGroup = TaskGroup::create($data);
        $response = collect($taskGroup->toArray())->only(['id', 'name', 'description']);
        $return['data'] = [
            'message' => 'Task Group created successfully',
            'task_group' => $response->toArray()
        ];
        $return['status'] = 201;
        return $return;
    }
}
