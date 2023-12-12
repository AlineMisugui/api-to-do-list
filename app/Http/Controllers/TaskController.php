<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Providers\Task\TaskCreateProvider;
use App\Providers\Task\TaskProvider;
use Illuminate\Http\JsonResponse;
use Throwable;

class TaskController extends Controller
{
    protected $taskCreateProvider;
    protected $taskUpdateProvider;
    protected $taskProvider;

    public function __construct(TaskCreateProvider $taskCreateProvider, TaskProvider $taskProvider){
        $this->taskCreateProvider = $taskCreateProvider;
        $this->taskProvider = $taskProvider;
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

    private function handleRequest($data, callable $callback) : JsonResponse {
        // try {
            $response = $callback($data);
            return response()->json($response, 200);
        // } catch (Throwable $th) {
        //     return response()->json(['error' => $th->getMessage()], $th->getCode() < 500 ? $th->getCode() : 500);
        // }
    }
}
