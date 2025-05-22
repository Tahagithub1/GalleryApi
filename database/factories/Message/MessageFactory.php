<?php

namespace Database\Factories\Message;

use App\Models\Message\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'message' => $this->faker->paragraph(),
        ];
    }

}
