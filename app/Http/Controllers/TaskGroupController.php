<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskGroup\TaskGroupCreateRequest;
use App\Http\Requests\TaskGroup\TaskGroupUpdateRequest;
use App\Providers\TaskGroup\TaskGroupCreateProvider;
use App\Providers\TaskGroup\TaskGroupProvider;
use App\Providers\TaskGroup\TaskGroupUpdateProvider;
use App\Providers\User\UserProvider;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class TaskGroupController extends Controller
{
    protected $taskGroupCreateProvider;
    protected $taskGroupUpdateProvider;
    protected $taskGroupProvider;

    public function __construct(TaskGroupCreateProvider $taskGroupCreateProvider, TaskGroupUpdateProvider $taskGroupUpdateProvider, TaskGroupProvider $taskGroupProvider)
    {
        $this->taskGroupCreateProvider = $taskGroupCreateProvider;
        $this->taskGroupUpdateProvider = $taskGroupUpdateProvider;
        $this->taskGroupProvider = $taskGroupProvider;
    }

    public function createTaskGroup(TaskGroupCreateRequest $request) : JsonResponse {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        return $this->handleRequest($validated, [$this->taskGroupCreateProvider, 'createTaskGroup']);
    }

    public function updateTaskGroup(TaskGroupUpdateRequest $request) : JsonResponse {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        return $this->handleRequest($validated, [$this->taskGroupUpdateProvider, 'updateTaskGroup']);
    }

    public function findTaskGroup(int $id) {
        $data = [
            'group_id' => $id,
            'user_id' => request()->user()->id
        ];
        return $this->handleRequest($data, [$this->taskGroupProvider, 'findTaskGroupForUser']);
    }

    public function getAllTaskGroups() {
        $data = [
            'user_id' => request()->user()->id
        ];
        return $this->handleRequest($data, [$this->taskGroupProvider, 'getAllTaskGroupsForUser']);
    }

    private function handleRequest($data, callable $callback) : JsonResponse {
        try {
            $response = $callback($data);
            return response()->json($response, 200);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], $th->getCode() < 500 ? $th->getCode() : 500);
        }
    }
}
