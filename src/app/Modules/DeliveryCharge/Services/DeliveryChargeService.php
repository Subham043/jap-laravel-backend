<?php

namespace App\Modules\DeliveryCharge\Services;

use App\Modules\DeliveryCharge\Models\DeliveryCharge;

class DeliveryChargeService
{

    public function get(): DeliveryCharge|null
    {
        $data = DeliveryCharge::where('delivery_charges_slug', 'delivery_charges')->first();
        return $data;
    }

    public function save(array $data): DeliveryCharge
    {
        $dt = DeliveryCharge::updateOrCreate(
            ['delivery_charges_slug'=> 'delivery_charges'],
            [...$data]);
        return $dt;
    }

}
