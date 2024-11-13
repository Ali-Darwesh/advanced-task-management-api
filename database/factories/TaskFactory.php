<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'type' => 'feature',
            'status' => 'open',
            'due_date' => $this->faker->date(),
            'assigned_to' => \App\Models\User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]),
        ];
    }
}
