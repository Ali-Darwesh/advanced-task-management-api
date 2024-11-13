<?php
// tests/Feature/TaskControllerTest.php
namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_task_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
        $this->actingAs($user);
        $data = [
            'title' => 'Test Task',
            'description' => 'Test description',
            'type' => 'feature',
            'status' => 'open',
            'priority' => 'low',
            'due_date' => '2024-11-20', // Correct format
            'assigned_to' => $user->id
        ];

        $response = $this->postJson('api/tasks', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'task created successfully']);
    }

    /** @test */
    // public function a_task_can_be_updated()
    // {

    //     $task = Task::factory()->create();

    //     $data = [
    //         'title' => 'Updated Task Title',
    //     ];

    //     $response = $this->putJson(route('api.update-task', ['task' =>  $task->id]), $data);
    //     $response->assertStatus(200);
    //     $response->assertJsonFragment(['message' => 'task updated successfully']);
    // }

    // /** @test */
    // public function a_comment_can_be_added_to_task()
    // {
    //     $user = User::factory()->create();
    //     $task = Task::factory()->create();
    //     $this->actingAs($user);

    //     $data = [
    //         'body' => 'This is a comment'
    //     ];

    //     $response = $this->postJson(route('tasks.addComment', $task->id), $data);
    //     $response->assertStatus(200)
    //         ->assertJsonFragment(['message' => 'comment added successfully']);
    // }

    // /** @test */
    // // public function a_task_can_have_attachments()
    // // {
    // //     $user = User::factory()->create();
    // //     $task = Task::factory()->create();
    // //     $this->actingAs($user);

    // //     $file = UploadedFile::fake()->image('attachment.jpg');

    // //     $response = $this->postJson(route('tasks.addAttachmentToTask', $task->id), [
    // //         'attachment' => $file
    // //     ]);

    // //     $response->assertStatus(200)
    // //         ->assertJsonFragment(['message' => 'Attachment added successfully']);
    // // }

    // /** @test */
    // public function a_dependency_can_be_added_to_task()
    // {
    //     $task1 = Task::factory()->create();
    //     $task2 = Task::factory()->create();

    //     $data = [
    //         'depends_on' => $task2->id
    //     ];

    //     $response = $this->postJson(route('tasks.addDependency', $task1->id), $data);
    //     $response->assertStatus(200)
    //         ->assertJsonFragment(['message' => 'Dependency added successfully!']);
    // }

    // /** @test */
    // public function a_task_status_can_be_updated()
    // {
    //     $user = User::factory()->create();
    //     $task = Task::factory()->create();
    //     $this->actingAs($user);

    //     $data = [
    //         'status' => 'completed'
    //     ];

    //     $response = $this->putJson(route('tasks.updateTaskStatus', $task->id), $data);
    //     $response->assertStatus(200)
    //         ->assertJsonFragment(['message' => 'task updated successfully']);
    // }
}
