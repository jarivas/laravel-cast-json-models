<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class SecondChance extends Model
{
    public bool $send_mail = false;
}
