<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class Account extends Model
{
    public string $address1;
    public string $address2;
    public string $address3;
    public string $apartment;
    public string $city;
    public string $coc_number;
    public string $company_name;
    public string $country = 'NL';
    public string $email;
    public int $id;
    public string $api_key;
    public string $phone;
    public string $vat_number;
    public string $zipcode;
}
