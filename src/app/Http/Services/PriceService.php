<?php

namespace App\Http\Services;

class PriceService
{
    private float $price;
    private float $discount;

    public function __construct(float $price, float|null $discount)
    {
        $this->price = $price;
        $this->discount = $discount ? $discount : 0;
    }

    public function getDiscountInPrice()
    {
        return round($this->discount/100, 2);
    }

    public function getDiscountedPrice()
    {
        return $this->discount==0 ? round($this->price, 2) : round($this->price - ($this->price * $this->getDiscountInPrice()), 2);
    }

    public function getPriceWithQuantity(int $quantity = 1)
    {
        return round($this->getDiscountedPrice() * $quantity, 2);
    }
}
