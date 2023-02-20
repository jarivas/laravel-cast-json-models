<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;

class TaxItem extends Model
{
    public bool $no_shipping_method = false;
    public bool $use_shipping_notification = false;
    public ShippingMethods $shipping_methods;
}
