<?php

declare(strict_types=1);

namespace App\Dto\Api\Auth;

use App\Dto\Base;

class LoginDto extends Base
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $remember,
    )
    {
        //
    }
}