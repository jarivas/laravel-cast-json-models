<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class Ubo extends Model
{
    public string $type;
    public bool $allow_contact;
    public string $mobile_phone;
    public string $name;
    public string $address_apartment;
    public string $country_of_birth;
    public string $country;
    public int $percentage;
    public string $city;
    public string $state;
    public string $zipcode;
    public string $gender;
    public string $title;
    public string $birthday;
    public string $office_phone;
    public string $job_title;
    public string $address;
    public string $email;
}
