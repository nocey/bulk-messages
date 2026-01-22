<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Message;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;
    public function definition(): array
    {
        return [
            'phone' => fake()->phoneNumber(),
            'content' => fake()->text(Message::MAX_CONTENT_LENGTH),
            'status' => fake()->randomElement(['pending', 'sent', 'failed']),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    
}
