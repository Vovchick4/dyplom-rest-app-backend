<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    private $price = 0;

    public function pivotAttached(Order $order)
    {
        $this->updatePrice($order);
    }

    public function pivotDetached(Order $order)
    {
        $this->updatePrice($order);
    }

    public function pivotUpdated(Order $order)
    {
        $this->updatePrice($order);
    }

    private function updatePrice($order)
    {
        $order->plates()->each(function ($item) {
            $this->price += $item->pivot->price * $item->pivot->amount;
        });

        $order->update(['price' => $this->price]);
    }

    public function creating(Order $order)
    {
        $order->code = $this->generateUniqueCode($order);
    }

    private function generateUniqueCode(Order $order)
    {
        $code = random_int(10000000, 99999999);

        if (Order::where('code', $code)->where('restaurant_id', $order->restaurant_id)->first()) {
            return $this->generateUniqueCode($order);
        }

        return $code;
    }
}
