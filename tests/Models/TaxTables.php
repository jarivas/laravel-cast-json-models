<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;

class TaxTables extends Model
{
    public TaxTableSelector $default;
}
