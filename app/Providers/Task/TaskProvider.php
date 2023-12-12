<?php

namespace App\Providers\Task;

use App\Models\Task;
use App\Models\TaskGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class TaskProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskProvider::class, function ($app) {
            return new TaskProvider($app);
        });
        $this->app->bind(TaskCreateProvider::class, function ($app) {
            return new TaskCreateProvider($app);
        });
        $this->app->bind(TaskUpdateProvider::class, function ($app) {
            return new TaskUpdateProvider($app);
        });
        $this->app->bind(TaskDeleteProvider::class, function ($app) {
            return new TaskDeleteProvider($app);
        });
    }

    private static function findTaskGroupByTask(Task $task): ?TaskGroup
    {
        $task_group = TaskGroup::where('id', $task->group_id)->first();
        if (!$task_group) {
            throw new Exception('Grupo de tarefa não encontrado', 404);
        }
        return $task_group;
    }

    public static function verifyifTaskBelongsToUser(Task $task, $user_id): void
    {
        $task_group = self::findTaskGroupByTask($task);
        if ($task_group->user_id != $user_id) {
            throw new Exception('Tarefa não pertence ao usuário', 401);
        }
    }

    public function getAllTasks(array $data): array
    {
        $tasks = DB::table('tasks')
        ->join('task_groups', 'task_groups.id', '=', 'tasks.group_id')
        ->where('task_groups.user_id', $data['user_id'])
        ->select('tasks.id', 'tasks.description', 'tasks.status', 'tasks.deadline',
                 DB::raw("JSON_UNQUOTE(JSON_OBJECT('id', task_groups.id, 'name', task_groups.name)) as task_group"))
        ->get();

        foreach ($tasks as $task) {
            $task->task_group = json_decode($task->task_group);
        }
        $return['data'] = $tasks->toArray();
        return $return;
    }

    protected function findTaskById(int $id): ?Task
    {
        $task = Task::where('id', $id)->first();
        if (!$task) {
            throw new Exception('Task not found', 404);
        }
        return $task;
    }

    public function findTask(array $dados): array
    {
        $task = $this->findTaskById($dados['id']);
        $this->verifyifTaskBelongsToUser($task, $dados['user_id']);
        $taskGroup = $this->findTaskGroupByTask($task);
        $dataTask = $this->formatData($task, $taskGroup);
        $return['data'] = $dataTask;
        return $return;
    }

    private function formatData(Task $task, TaskGroup $taskGroup): array
    {
        $task = $task->only(['id', 'description', 'status', 'deadline']);
        $taskGroup = $taskGroup->only(['id', 'name', 'description']);
        $dataTask = $task;
        $dataTask['task_group'] = $taskGroup;
        return $dataTask;
    }
}
