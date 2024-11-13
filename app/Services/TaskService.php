<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class TaskService
{
    /**
     * service to create task.
     * @param array $data
     * @return array 

     */
    public function createTask(array $data)
    {
        try {
            $task = Task::create($data);
            return ['message' => 'task created successfully', 'task' => $task, 'status' => 200];
        } catch (Exception $e) {
            Log::error('Error creating Task: ' . $e->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'there is something wrong in server'], status: 500));
        }
    }
    /**
     * service to update task data in storage.
     * @param Task $task
     * @param array $data
     * @return array 

     */
    public function updateTask(Task $task, array $data)
    {
        try {
            $task = Task::findOrFail($task->id);
            $task->update($data);
            return ['message' => 'task updated successfully', 'task' => $task, 'status' => 200];
        } catch (Exception $e) {
            Log::error('Error updating Task: ' . $e->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'there is something wrong in server'], status: 500));
        }
    }
    /**
     * service method used to delete task from storage.
     * @param Task $task
     *@return array

     */
    public function deleteTask(Task $task)
    {
        try {
            $task = Task::findOrFail($task->id);
            $task->delete();
            return ['message' => 'Task deleted successfully', 'status' => 200];
        } catch (Exception $e) {
            Log::error('Error deleting Task: ' . $e->getMessage());
            throw new HttpResponseException(response()->json(['message' => 'there is something wrong in server'], status: 500));
        }
    }
    // Update the status of dependent tasks when their dependencies are changed
    public function updateDependentTasksStatus(Task $task)
    {
        try {
            // Find tasks that depend on this task
            $dependentTasks = $task->dependentTasks;

            foreach ($dependentTasks as $dependentTask) {
                // If the dependent task is blocked, we need to check if all dependencies are completed
                if ($dependentTask->status === 'blocked') {
                    $allDependenciesClosed = $dependentTask->dependencies()->where('status', '!=', 'completed')->doesntExist();

                    // If all dependencies are completed or closed, set the dependent task's status to "open"
                    if ($allDependenciesClosed) {
                        $dependentTask->status = 'open';
                        $dependentTask->save();
                    }
                }
            }
            return ['message' => 'update Dependent Tasks Status successfully', 'data' => $dependentTasks, 'status' => 200];
        } catch (Exception $e) {
            return ['message' => 'update Dependent Tasks Status failed', 'error' => $e, 'status' => 404];
        }
    }
}
