<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class RelatedTransaction extends Model
{
    public int $amount;

    /** \Tests\Feature\Model\Cost */
    public Collection $costs;
    public string $created;
    public string $currency;
    public string $description;
    public string $modified;
    public string $status;
    public int $transaction_id;
}
