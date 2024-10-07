<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class Weight extends Model
{
    public string $unit;
    public int $value;
}
