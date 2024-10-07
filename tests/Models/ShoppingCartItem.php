<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class ShoppingCartItem extends Model
{
    public string $name;
    public string $description;
    public float $unit_price;
    public int $quantity;
    public string $merchant_item_id;
    public string $tax_table_selector;
    public Weight $weight;
}
