<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;

class Cost extends Model
{
    public int $transaction_id;
    public int $amount;
    public string $description;
    public string $type;
    public string $currency;
    public string $created;
    public string $status;
}
