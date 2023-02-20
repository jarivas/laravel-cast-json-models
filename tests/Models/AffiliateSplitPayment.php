<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;

class AffiliateSplitPayment extends Model
{
    public string $merchant;
    public int $fixed;
    public float $percentage;
    public string $description;
}
