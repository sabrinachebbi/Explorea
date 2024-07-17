<?php
namespace App\Enum;

enum modePayment: string
{
    case PAYPAL = 'PAYPAL';
    case BANK_CARD = 'card';

}