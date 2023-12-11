<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskGroup\TaskGroupCreateRequest;
use App\Providers\TaskGroup\TaskGroupCreateProvider;
use App\Providers\TaskGroup\TaskGroupProvider;
use App\Providers\TaskGroup\TaskGroupUpdateProvider;
use Illuminate\Http\JsonResponse;
use Throwable;

class TaskGroupController extends Controller
{
    protected $taskGroupCreateProvider;
    protected $taskGroupUpdateProvider;

    public function __construct(TaskGroupCreateProvider $taskGroupCreateProvider)
    {
        $this->taskGroupCreateProvider = $taskGroupCreateProvider;
    }

    public function createTaskGroup(TaskGroupCreateRequest $request) : JsonResponse {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        return $this->handleRequest($validated, [$this->taskGroupCreateProvider, 'createTaskGroup']);
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
