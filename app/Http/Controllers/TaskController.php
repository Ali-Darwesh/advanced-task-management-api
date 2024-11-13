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
        if ($task['task']->status == "completed") {
            $this->updateDependentTasksStatus($task);
        }

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


    public function addDependency(Request $request, $taskId)
    {
        $request->validate([
            'depends_on' => 'exists:tasks,id', // Make sure each dependency exists in the 'tasks' table
        ]);
        $dependencyId = $request->input('depends_on');
        if ($taskId == $dependencyId) {
            return response()->json(['error' => 'A task cannot depend on itself.'], 400);
        }
        $task = Task::findOrFail($taskId);
        $dependency = Task::findOrFail($request->input('depends_on'));
        if ($task->dependencies()->where('depends_on_task_id', $dependencyId)->exists()) {
            return response()->json(['error' => 'This dependency already exists.'], 409);
            // الحصول على المهمة الأصلية والمهمة المعتمدة
        }
        if ($dependency->dependencies()->where('depends_on_task_id', $taskId)->exists()) {
            return response()->json(['error' => 'This dependency not correct.'], 409);
            // الحصول على المهمة الأصلية والمهمة المعتمدة
        }

        // إضافة التبعية بين المهام
        $task->dependencies()->attach($dependency);

        // إذا كانت المهمة المعتمدة غير مكتملة، قم بتعيين حالة المهمة إلى Blocked
        if ($dependency->status !== 'completed') {
            $task->status = 'blocked';
            $task->save();
        }

        return response()->json([
            'message' => 'Dependency added successfully!',
            'task' => $task
        ]);
    }

    // Method to remove a single dependency from a task
    public function removeDependency(Request $request, $taskId)
    {
        // Validate the input data
        $request->validate([
            'depends_on' => 'required|exists:tasks,id', // The task ID to remove from dependencies
        ]);

        // Find the task from which dependencies will be removed
        $task = Task::findOrFail($taskId);

        // Detach the specified dependency
        $task->dependencies()->detach($request->input('depends_on'));

        return response()->json(['message' => 'Dependency removed successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
