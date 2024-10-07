<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class BankAccount extends Model
{
    public string $country;
    public string $currency;
    public string $iban;
    public string $bank_abbreviation;
    public string $holder_name;
}
