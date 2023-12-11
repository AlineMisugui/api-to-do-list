<?php

namespace App\Providers\TaskGroup;
use App\Models\TaskGroup;
use App\Providers\User\UserProvider;

class TaskGroupUpdateProvider extends TaskGroupProvider
{
    public function updateTaskGroup(array $data) : array {
        $this->verifyIfUserIsActive($data['user_id']);
        $taskGroup = $this->findTaskGroup($data['id']);
        $this->verifyIfGroupBelongsToUser($data['user_id'], $taskGroup->user_id);
        $taskGroupUpdated = $this->update($taskGroup, $data);
        $formatedData = $this->formatTaskGroup($taskGroupUpdated);
        return $formatedData;
    }

    private function verifyIfUserIsActive(int $userId) : void {
        $user = UserProvider::findUserById($userId);
        $userIsActive = UserProvider::verifyIfUserIsActive($user);
        if (!$userIsActive) {
            throw new \Exception('User inactive', 401);
        }
    }

    private function findTaskGroup(int $id) : ?TaskGroup {
        $taskGroup = TaskGroup::where('id', $id)->first();
        if (!$taskGroup) {
            throw new \Exception('Task Group not found', 404);
        }
        return $taskGroup;
    }

    private function update(TaskGroup $taskGroup, array $data) : ?TaskGroup {
        $taskGroup->name = $data['name'] ?? $taskGroup->name;
        $taskGroup->description = $data['description'] ?? $taskGroup->description;
        $taskGroup->save();
        return $taskGroup;
    }

    private function formatTaskGroup(TaskGroup $taskGroup) : array {
        $description = $taskGroup->description;
        if (!$description) {
            $description = '';
        }
        return [
            'id' => $taskGroup->id,
            'name' => $taskGroup->name,
            'description' => $description
        ];
    }
}
