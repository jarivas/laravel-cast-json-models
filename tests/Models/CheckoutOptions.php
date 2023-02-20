<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class CheckoutOptions extends Model
{
    public bool $validate_cart = false;
    public TaxTables $tax_tables;
    /** \Tests\Feature\Models\TaxItem */
    public Collection $alternate;
}
