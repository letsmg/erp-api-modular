<?php

namespace App\Modules\Auth\Tests\Feature;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_endpoint_is_accessible(): void
    {
        $this->getJson(route('api.login'))->assertOk();
    }

    public function test_user_can_login_and_logout_with_json_responses(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'Mudar@123',
        ])->assertOk();

        $this->actingAs($user)
            ->postJson(route('api.logout'))
            ->assertOk();
    }
}
