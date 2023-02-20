<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;

class FcoSettings extends Model
{
    public string $redirect_mode;
    public Disabled $coupons;
    public Disabled $cart;
    public Disabled $shipping;
    public string $issuers_display_mode;
    public Enabled $checkout;
    public bool $group_cards;
}
