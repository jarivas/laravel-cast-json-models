<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class GatewayInfo extends Model
{
    public string $card_number;
    public string $card_holder_name;
    public string $card_expiry_date;
    public string $card_cvc;
    public string $flexible_3d;
    public bool $moto = false;
    public string $term_url;
    public string $issuer_id;
    public string $payment_token;
    public bool $qr_enabled = false;
    public int $qr_size = 0;
    public bool $allow_multiple = false;
    public bool $allow_change_amount = false;
    public string $min_amount;
    public string $max_amount;
    public string $account_id;
    public string $account_holder_name;
    public string $account_holder_iban;
    public string $emandate;
    public string $birthday;
    public string $bank_account;
    public string $phone;
    public string $email;
}
