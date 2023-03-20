<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->limit(1)->first(),
            'body' => $this->faker->sentence(),
            'commentable_id' => 1,
            'commentable_type' => \App\Models\User::class,
        ];
    }

    /**
     * Indicate that the comment is approved.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function approved()
    {
        return $this->state(function () {
            return [
                'is_approved' => true,
            ];
        });
    }
}
