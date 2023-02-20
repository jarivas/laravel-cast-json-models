<?php

namespace Tests\Feature;

use Tests\Feature\Models\PaymentOptions;
use Tests\Feature\Models\Customer;
use Tests\Feature\Models\CheckoutOptions;
use Tests\Feature\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function testPaymentOptions()
    {
        $data = $this->getData();
        $data = $data['payment_options'];

        $paymentOptions = new PaymentOptions($data);
        $this->assertInstanceOf(PaymentOptions::class, $paymentOptions);

        $this->assertSame($data['close_window'], $paymentOptions->close_window);
        $this->assertSame($data['notification_method'], $paymentOptions->notification_method);
        $this->assertSame($data['notification_url'], $paymentOptions->notification_url);
        $this->assertSame($data['redirect_url'], $paymentOptions->redirect_url);
        $this->assertSame($data['cancel_url'], $paymentOptions->cancel_url);
    }

    public function testCustomer()
    {
        $data = $this->getData();
        $data = $data['customer'];

        $customer = new Customer($data);
        $this->assertInstanceOf(Customer::class, $customer);

        $this->assertSame($data['locale'], $customer->locale);
    }

    public function testCheckoutOptions()
    {
        $data = $this->getData();
        $data = $data['checkout_options'];

        $checkoutOptions = new CheckoutOptions($data);
        $this->assertInstanceOf(CheckoutOptions::class, $checkoutOptions);

        $this->assertSame($data['validate_cart'], $checkoutOptions->validate_cart);
    }

    public function testOrder()
    {
        $data = $this->getData();

        $order = new Order($data);
        $this->assertInstanceOf(Order::class, $order);

        $this->assertSame($data['days_active'], $order->days_active);
        $this->assertSame($data['seconds_active'], $order->seconds_active);
        $this->assertSame($data['type'], $order->type);
        $this->assertSame($data['order_id'], $order->order_id);
        $this->assertSame($data['currency'], $order->currency);
        $this->assertSame($data['amount'], $order->amount);
        $this->assertSame($data['description'], $order->description);
    }

    private function getData(): array
    {
        $filePath = __DIR__.'/order.json';
        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);

        $jsonData = file_get_contents($filePath);

        $this->assertIsString($jsonData);

        $data = json_decode($jsonData, true);

        $this->assertNotEmpty($data);
        $this->assertIsArray($data);

        return $data;
    }
}

