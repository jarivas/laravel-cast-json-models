<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class Affiliate extends Model
{
    /** \Tests\Feature\Models\AffiliateSplitPayment */
    public Collection $split_payments;
}
