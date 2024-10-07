<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class Site extends Model
{
    public int $account_id;
    public string $api_key;
    public int $id;
    public string $name;
    public string $notification_url;
    public string $price_from;
    public string $price_till;
    public string $support_email;
    public string $support_phone;
    public string $url;
}
