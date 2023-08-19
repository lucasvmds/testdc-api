<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_can_fetch_all_users(): void
    {
        $user = User::factory()->create();
        $response = $this
                        ->withAuth()
                        ->get('/api/users');
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1, fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $user->name)
                            ->where('email', $user->email)
                            ->etc()
                    )
            );
    }

    public function test_can_create_user(): void
    {
        $user_data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'Ab123456789@',
            'password_confirmation' => 'Ab123456789@',
            'role' => UserRole::ADMIN,
        ];
        $response = $this
                        ->withAuth()
                        ->postJson('/api/users', $user_data);
        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $user_data['name'])
                            ->where('email', $user_data['email'])
                            ->where('role', $user_data['role']->value)
                            ->etc()
                    )
            );
    }

    public function test_can_fetch_user(): void
    {
        $user = User::factory()->create();
        $response = $this
                        ->withAuth()
                        ->get("/api/users/$user->id");
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $user->name)
                            ->where('email', $user->email)
                            ->where('role', $user->role->value)
                            ->etc()
                    )
            );
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->create();
        $user->name = fake()->name();
        $user->email = fake()->email();
        $response = $this
                        ->withAuth()
                        ->patchJson("/api/users/$user->id", [
                            'name' => $user->name,
                            'email' => $user->email,
                            'password' => 'Ab123456789@',
                            'password_confirmation' => 'Ab123456789@',
                            'role' => $user->role,
                        ]);
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $user->name)
                            ->where('email', $user->email)
                            ->where('role', $user->role->value)
                            ->etc()
                    )
            );
    }

    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();
        $response = $this
                        ->withAuth()
                        ->delete("/api/users/$user->id");
        $response->assertStatus(204);
        $this->assertModelMissing($user);
    }
}
