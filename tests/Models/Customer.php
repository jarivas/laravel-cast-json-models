<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class Customer extends Model
{
    public string $locale;
    public string $ip_address;
    public string $forwarded_ip;
    public string $first_name;
    public string $last_name;
    public string $gender;
    public string $birthday;
    public string $address1;
    public string $address2;
    public string $house_number;
    public string $zip_code;
    public string $city;
    public string $state;
    public string $country;
    public string $phone;
    public string $email;
    public string $user_agent;
    public string $referrer;
    public string $reference;
}
