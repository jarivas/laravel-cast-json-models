<?php

declare(strict_types=1);

namespace CastModels\Tests\Models;

use CastModels\Model;

class CustomField extends Model
{
    public string $standard_type;
    public string $name;
    public string $type;
    public string $label;
    public DescriptionRight $description_right;
    public CustomFieldValidation $validation;
}
