<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class ShoppingCart extends Model
{
    /** \Tests\Feature\Models\ShoppingCartItem */
    public Collection $items;
}
