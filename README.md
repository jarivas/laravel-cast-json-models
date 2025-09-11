# Laravel json casting

- A easy solution to have decriptions of json objects POCO style
- The method **Object::collection** will convert an array of objects to an collection of PHP objects that extends from CastModels\Model
- The objects that extends from CastModels\Model has the method **toArray** so can be returned in a response directly
- self casts from a Json object or array of json objects
```php
<?php

namespace Tests\Models;

use CastModels\Model;
use Illuminate\Support\Collection;
use Tests\Enum\OrderStatus;
use Tests\Enum\TransactionStatus;

class Order extends Model
{
    public int $transaction_id;

    public string $order_id;

    public string $created;

    public string $currency = 'EUR';

    public int $amount;

    public string $description;

    /** @var Collection<\App\Casts\ShoppingCartItem> */
    public Collection $items;

    public int $amount_refunded;

    public string $auth_order_id;

    public OrderStatus $status;

    public TransactionStatus $financial_status;

    public string $reason;

    public string $reason_code;

    public string $fastcheckout;

    public string $modified;

    public Customer $customer;

    public PaymentDetails $payment_details;

    public string $completed_date;

    public Costs $costs;

    /** @var Collection<\App\Casts\Transaction> */
    public Collection $related_transactions;

    /** @var Collection<\App\Casts\PaymentMethod> */
    public Collection $payment_methods;

    public string $var1;

    public string $var2;

    public string $var3;
}
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Casts\Translation;
use Illuminate\Database\Eloquent\Collection;

/**
 * Summary of Example
 *
 * @property string $id
 * @property string $name
 * @property \Illuminate\Support\Collection $name_translations
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class Example extends Model
{
    protected $fillable = [
        'name',
        'name_translations',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // This will be valid for a single object or array of objects
            'name_translations' => Translation::class,
        ];
    }
}

```