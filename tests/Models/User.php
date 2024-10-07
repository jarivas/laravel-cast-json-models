<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class User extends Model
{
    public string $name;
    public string $password;
    public string $email;
}
