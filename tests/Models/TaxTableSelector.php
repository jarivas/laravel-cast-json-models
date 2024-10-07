<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class TaxTableSelector extends Model
{
    public bool $shipping_taxed = false;
    public float $rate;
}
