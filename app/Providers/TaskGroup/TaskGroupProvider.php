<?php

namespace App\Providers\TaskGroup;

use App\Models\TaskGroup;
use Exception;
use Illuminate\Support\ServiceProvider;

class TaskGroupProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskGroupProvider::class, function ($app) {
            return new TaskGroupProvider($app);
        });
        $this->app->bind(TaskGroupCreateProvider::class, function ($app) {
            return new TaskGroupCreateProvider($app);
        });
        $this->app->bind(TaskGroupUpdateProvider::class, function ($app) {
            return new TaskGroupUpdateProvider($app);
        });
    }

    public static function findTaskGroupById(int $id): ?TaskGroup
    {
        $taskGroup = TaskGroup::find($id);
        if (!$taskGroup) {
            throw new Exception('Task Group not found', 404);
        }
        return $taskGroup;
    }

    public function findTaskGroupForUser(array $data): ?array
    {
        $group_id = $data['group_id'];
        $user_id = $data['user_id'];
        $taskGroup = $this->findTaskGroupById($group_id);
        if (!$taskGroup) {
            throw new Exception('Task Group not found', 404);
        }
        $this->verifyIfGroupBelongsToUser($user_id, $taskGroup->user_id);
        if (!$taskGroup) {
            throw new Exception('Task Group not found', 404);
        }
        return $taskGroup->only(['id', 'name', 'description']);
    }

    public function getAllTaskGroupsForUser(array $data): array
    {
        $user_id = $data['user_id'];
        $taskGroups = TaskGroup::where('user_id', $user_id)->get();
        return $taskGroups->map(function ($taskGroup) {
            return $taskGroup->only(['id', 'name', 'description']);
        })->toArray();
    }

    public function deleteTaskGroupForUser(array $data): array
    {
        $group_id = $data['group_id'];
        $user_id = $data['user_id'];
        $taskGroup = $this->findTaskGroupById($group_id);
        if (!$taskGroup) {
            throw new Exception('Task Group not found', 404);
        }
        $this->verifyIfGroupBelongsToUser($user_id, $taskGroup->user_id);
        if (!$taskGroup) {
            throw new Exception('Task Group not found', 404);
        }
        $taskGroup->delete();
        return ['message' => 'Task Group deleted successfully'];
    }

    public static function verifyIfGroupBelongsToUser(int $userId, int $realUserId): void
    {
        if ($userId !== $realUserId) {
            throw new Exception('User not authorized', 401);
        }
    }
}
