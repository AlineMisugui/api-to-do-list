<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Providers\Task\TaskCreateProvider;
use App\Providers\Task\TaskDeleteProvider;
use App\Providers\Task\TaskProvider;
use App\Providers\Task\TaskUpdateProvider;
use Illuminate\Http\JsonResponse;
use Throwable;

class TaskController extends Controller
{
    protected $taskCreateProvider;
    protected $taskUpdateProvider;
    protected $taskProvider;
    protected $taskDeleteProvider;

    public function __construct(TaskCreateProvider $taskCreateProvider, TaskProvider $taskProvider, TaskUpdateProvider $taskUpdateProvider, TaskDeleteProvider $taskDeleteProvider){
        $this->taskProvider = $taskProvider;
        $this->taskCreateProvider = $taskCreateProvider;
        $this->taskUpdateProvider = $taskUpdateProvider;
        $this->taskDeleteProvider = $taskDeleteProvider;
    }

    public function createTask(TaskCreateRequest $request) : JsonResponse {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        return $this->handleRequest($validated, [$this->taskCreateProvider, 'createTask']);
    }

    public function getAllTasks() : JsonResponse {
        $data['user_id'] = request()->user()->id;
        return $this->handleRequest($data, [$this->taskProvider, 'getAllTasks']);
    }

    public function findTask(int $id) : JsonResponse {
        $data['id'] = $id;
        $data['user_id'] = request()->user()->id;
        return $this->handleRequest($data, [$this->taskProvider, 'findTask']);
    }

    public function updateTask(TaskUpdateRequest $request, int $id) : JsonResponse {
        $validated = $request->validated();
        $validated['id'] = $id;
        $validated['user_id'] = $request->user()->id;
        return $this->handleRequest($validated, [$this->taskUpdateProvider, 'updateTask']);
    }

    public function deleteTask(int $id) : JsonResponse {
        $data['id'] = $id;
        $data['user_id'] = request()->user()->id;
        return $this->handleRequest($data, [$this->taskDeleteProvider, 'deleteTask']);
    }

    private function handleRequest($data, callable $callback) : JsonResponse {
        try {
            $result = $callback($data);
            $response = $result['data'];
            $status = isset($result['status']) ? $result['status'] : 200;
            return response()->json($response, $status);
        } catch (Throwable $th) {
            return response()->json(['error' => $th->getMessage()], $th->getCode() < 500 ? $th->getCode() : 500);
        }
    }
}
