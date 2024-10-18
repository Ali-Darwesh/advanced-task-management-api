<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest\AddCommentRequest;
use App\Http\Requests\TaskRequest\ReassignTaskRequest;
use App\Http\Requests\TaskRequest\StoreTaskRequest;
use App\Http\Requests\TaskRequest\UpdateTaskRequest;
use App\Http\Requests\TaskRequest\UpdateTaskStatusRequest;
use App\Models\Attachment;
use App\Models\Task;
use App\Services\CommentService;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Constracor to inject task Service
     * @param TaskService $taskService
     */
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {}



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->createTask($validatedData);
        return $this->success($task['task'], $task['message'], $task['status']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task) {}



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->updateTask($task, $validatedData);
        return $this->success($task['task'], $task['message'], $task['status']);
    }
    /**
     * Update the specified resource in storage.
     */
    public function updateTaskStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->updateTask($task, $validatedData);
        return $this->success($task['task'], $task['message'], $task['status']);
    }
    /**
     * Update the specified resource in storage.
     */
    public function reassignTask(ReassignTaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->updateTask($task, $validatedData);
        return $this->success($task['task'], $task['message'], $task['status']);
    }

    public function addComment(AddCommentRequest $request, $id)
    {
        $validatedData = $request->validated();
        $task = Task::where('id', $id)->first();
        $task = $task->comments()->create($validatedData);
        return $this->success($task, 'comment added successfully');
    }
    public function addAttachmentToTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments');

            $attachment = Attachment::create([
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
            ]);

            $task->attachments()->attach($attachment->id);
        }

        return response()->json(['message' => 'Attachment added successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
