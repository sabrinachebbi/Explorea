<?php
namespace App\Enum;
enum statusPaym: string{
    case PAYMENT_PENDING = 'payment_pending';
    case PAYMENT_APPROVED = 'payment_approved';
    case PAYMENT_Cancelled = 'payment_cancelled';
}