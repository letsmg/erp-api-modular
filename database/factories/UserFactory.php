<?php

namespace Database\Factories;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$12$lzb.YMfP5wqQ9E3DkxqQ7OwJWuKve1pD0vDxn4..fM1a2RW.kFGla', // password
            'remember_token' => Str::random(10),
            'access_level' => 0,
            'is_active' => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function admin(): static
    {
        return $this->state(fn () => ['access_level' => 1]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
