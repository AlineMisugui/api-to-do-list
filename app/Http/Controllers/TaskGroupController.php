<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskGroup\TaskGroupCreateRequest;
use App\Http\Requests\TaskGroup\TaskGroupUpdateRequest;
use App\Providers\TaskGroup\TaskGroupCreateProvider;
use App\Providers\TaskGroup\TaskGroupProvider;
use App\Providers\TaskGroup\TaskGroupUpdateProvider;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class TaskGroupController extends Controller
{
    protected $taskGroupCreateProvider;
    protected $taskGroupUpdateProvider;

    public function __construct(TaskGroupCreateProvider $taskGroupCreateProvider, TaskGroupUpdateProvider $taskGroupUpdateProvider)
    {
        $this->taskGroupCreateProvider = $taskGroupCreateProvider;
        $this->taskGroupUpdateProvider = $taskGroupUpdateProvider;
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

    private function handleRequest($data, callable $callback) : JsonResponse {
        try {
            $response = $callback($data);
            return response()->json($response, 200);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], $th->getCode() < 500 ? $th->getCode() : 500);
        }
    }
}
