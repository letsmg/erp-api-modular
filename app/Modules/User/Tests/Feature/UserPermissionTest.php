<?php

namespace App\Modules\User\Tests\Feature;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_receives_unauthorized_for_protected_route(): void
    {
        $this->getJson(route('api.users.index'))->assertUnauthorized();
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->create(['access_level' => 1]);

        $this->actingAs($admin)
            ->getJson(route('api.users.index'))
            ->assertOk()
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_operator_only_sees_non_admin_users(): void
    {
        $user = User::factory()->create(['access_level' => 0]);
        User::factory()->create(['access_level' => 0]);
        User::factory()->create(['access_level' => 1]);

        $response = $this->actingAs($user)->getJson(route('api.users.index'));

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['access_level' => 1]);

        $payload = [
            'name' => 'Clone',
            'email' => 'clone@teste.com',
            'password' => 'Senha@Forte123',
            'password_confirmation' => 'Senha@Forte123',
            'access_level' => 0,
            'is_active' => true,
        ];

        $this->actingAs($admin)
            ->postJson(route('api.users.store'), $payload)
            ->assertCreated();

        $this->assertDatabaseHas('users', ['email' => 'clone@teste.com']);
    }

    public function test_operator_cannot_delete_users(): void
    {
        $user = User::factory()->create(['access_level' => 0]);
        $target = User::factory()->create(['access_level' => 1]);

        $this->actingAs($user)
            ->deleteJson(route('api.users.destroy', $target))
            ->assertForbidden();
    }
}
