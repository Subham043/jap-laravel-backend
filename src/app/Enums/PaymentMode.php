<?php

namespace App\Enums;

enum PaymentMode:string {
    case COD = 'Cash On Delivery';
    case ONLINE = 'Online';
}
