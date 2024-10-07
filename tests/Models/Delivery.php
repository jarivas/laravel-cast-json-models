<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class Delivery extends Model
{
    public string $first_name;
    public string $last_name;
    public string $address1;
    public string $house_number;
    public string $zip_code;
    public string $city;
    public string $country;
    public string $phone;
    public string $email;
}
