<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use CastModels\Model;
use Illuminate\Support\Collection;

class Order extends Model
{
    public string $type;
    public string $order_id;
    public string $currency;
    public int $amount;
    public string $description;
    public PaymentOptions $payment_options;
    public Customer $customer;
    public GatewayInfo $gateway_info;
    public Delivery $delivery;
    public CheckoutOptions $checkout_options;
    public ShoppingCart $shopping_cart;
    /** \Tests\Feature\Model\CustomField */
    public Collection $custom_fields;
    public Affiliate $affiliate;
    public SecondChance $second_chance;
    public int $days_active = 0;
    public int $seconds_active = 0;
    public string $var1;
    public string $var2;
    public string $var3;
    public Plugin $plugin;

    public int $transaction_id;
    public string $created;
    public string $items;
    public int $amount_refunded;
    public string $status;
    public string $financial_status;
    public string $fastcheckout;
    public string $modified;
    /** \Tests\Feature\Model\Cost */
    public Collection $costs;
    /** \Tests\Feature\Model\RelatedTransaction */
    public Collection $related_transactions;
    /** \Tests\Feature\Model\PaymentMethod */
    public Collection $payment_methods;
}
