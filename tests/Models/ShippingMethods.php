<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class ShippingMethods extends Model
{
    public ShippingMethodPickup $pickup;

    /** \Tests\Feature\Models\ShippingMethod */
    public Collection $flat_rate_shipping;
}
