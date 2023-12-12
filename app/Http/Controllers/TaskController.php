<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Providers\Task\TaskCreateProvider;
use Illuminate\Http\JsonResponse;
use Throwable;

class TaskController extends Controller
{
    protected $taskCreateProvider;
    protected $taskUpdateProvider;
    protected $taskProvider;

    public function __construct(TaskCreateProvider $taskCreateProvider){
        $this->taskCreateProvider = $taskCreateProvider;
    }

    public function createTask(TaskCreateRequest $request) : JsonResponse {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        return $this->handleRequest($validated, [$this->taskCreateProvider, 'createTask']);
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
