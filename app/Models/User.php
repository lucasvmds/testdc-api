<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use App\Http\Requests\Api\PaginateRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    public static function getAll(PaginateRequest $request): LengthAwarePaginator
    {
        return static::query()
                            ->orderBy('name')
                            ->where('id', '!=', static::current()->id)
                            ->paginate($request->validated('items', 20));
    }

    public static function create(array $data): self
    {
        $data['password'] = Hash::make($data['password']);
        return static::query()->create($data);
    }

    public function updateRecord(array $data): void
    {
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $this->update($data);        
    }

    public static function login(array $data): static | false
    {
        $user = User::query()
                        ->where('email', $data['email'])
                        ->first();
        if (!$user) return false;
        return Hash::check($data['password'], $user->password) ? $user : false;
    }

    public function generateToken(bool $remember): string
    {
        $this->cleanTokens();
        $expiration = $remember ? null : now()->addHour();
        return $this
                    ->createToken(name: 'access_token', expiresAt: $expiration)
                    ->plainTextToken;
    }

    public static function logout(): void
    {
        static::current()->cleanTokens();
    }

    public function cleanTokens(): void
    {
        $this
            ->tokens()
            ->delete();
    }

    public static function current(): static | false
    {
        /** @var Request */
        $request = app(Request::class);
        $access_token = PersonalAccessToken::findToken($request->bearerToken());
        if (!$access_token) return false;
        return $access_token->tokenable()->first() ?? false;
    }
}
