<?php

namespace App\Providers\TaskGroup;

use App\Models\TaskGroup;
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

    protected function findTaskGroupById(int $id): ?TaskGroup
    {
        return TaskGroup::find($id);
    }

    public function findTaskGroupForUser(array $data): ?array
    {
        $group_id = $data['group_id'];
        $userId = $data['user_id'];
        $taskGroup = $this->findTaskGroupById($group_id);
        $this->verifyIfGroupBelongsToUser($userId, $taskGroup->user_id);
        if (!$taskGroup) {
            throw new \Exception('Task Group not found', 404);
        }
        return $taskGroup->only(['id', 'name', 'description']);
    }

    public function getAllTaskGroupsForUser(array $data): array
    {
        $userId = $data['user_id'];
        $taskGroups = TaskGroup::where('user_id', $userId)->get();
        return $taskGroups->map(function ($taskGroup) {
            return $taskGroup->only(['id', 'name', 'description']);
        })->toArray();
    }

    protected function verifyIfGroupBelongsToUser(int $userId, int $realUserId): void
    {
        if ($userId !== $realUserId) {
            throw new \Exception('User not authorized', 401);
        }
    }
}
