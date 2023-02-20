<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;

class PaymentMethod extends Model
{
    public string $account_bic;
    public string $account_holder_name;
    public string $account_iban;
    public int $amount;
    public int $card_expiry_date;
    public string $currency;
    public string $description;
    public int $external_transaction_id;
    public int $last4;
    public string $payment_description;
    public string $status;
    public string $type;
}
