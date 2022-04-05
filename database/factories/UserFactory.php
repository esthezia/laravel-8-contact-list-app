<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => 'admin@yahoo.com',
            'password' => '$2y$10$kJwGY81vemvB4EjKYzfnn.cSrVXTmqKYYOVdevtcYquowwG97mUq6', // password
        ];
    }
}
