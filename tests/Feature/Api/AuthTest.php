<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_can_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('123456789'),
        ]);
        $response = $this->postJson('/api/auth', [
            'email' => $user->email,
            'password' => '123456789',
            'remember' => false,
        ]);
        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) =>
                $json
                    ->where('data.user.id', $user->id)
                    ->where('data.user.name', $user->name)
                    ->etc()
            );
    }

    public function test_can_logout(): void
    {
        $response = $this
                        ->withAuth()
                        ->deleteJson('/api/auth');
        $response->assertStatus(204);
        $this->assertDatabaseCount((new PersonalAccessToken)->getTable(), 0);
    }
}
