<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class ShippingMethod extends Model
{
    public string $id;
    public string $name;
    public string $price;
    public Collection $allowed_areas;
    public Collection $excluded_areas;
}
