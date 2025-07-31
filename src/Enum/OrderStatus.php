<?php

namespace App\Enum;

enum OrderStatus: string
{
    case ORDER_PLACED = "Order Placed";
    case PROCESSING = "Processing";
    case SHIPPED = "Shipped";
    case OUT_FOR_DELIVERY = "Out for Delivery";
    case DELIVERED = "Delivered";
}
