<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class CustomFieldValidation extends Model
{
    public string $type;
    public string $data;
    /** \Tests\Feature\Models\CustomFieldValidationError */
    public Collection $error;
}
