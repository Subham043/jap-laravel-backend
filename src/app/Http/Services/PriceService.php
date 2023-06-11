<?php

namespace App\Http\Services;

class PriceService
{
    private float $price;
    private float $discount;

    public function __construct(float $price, float $discount)
    {
        $this->price = $price;
        $this->discount = $discount;
    }

    public function getDiscountInPrice()
    {
        return $this->discount/100;
    }

    public function getDiscountedPrice()
    {
        return $this->discount==0 ? $this->price : $this->price - ($this->price * $this->getDiscountInPrice());
    }

    public function getPriceWithQuantity(int $quantity = 1)
    {
        return $this->getDiscountedPrice() * $quantity;
    }
}
