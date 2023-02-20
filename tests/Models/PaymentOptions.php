<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;

class PaymentOptions extends Model
{
    public string $notification_url;
    public string $notification_method;
    public string $feed_url;
    public string $redirect_url;
    public string $cancel_url;
    public bool $close_window;
    public Settings $settings;
}
