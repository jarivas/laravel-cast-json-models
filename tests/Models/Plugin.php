<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class Plugin extends Model
{
    public string $shop;
    public string $shop_version;
    public string $plugin_version;
    public string $partner;
    public string $shop_root_url;
}
