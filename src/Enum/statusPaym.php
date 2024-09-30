<?php
namespace App\Enum;
enum statusPaym: string{
    case PAYMENT_PENDING = 'paiement en attente';
    case PAYMENT_APPROVED = 'paiement_approuvéd';
    case PAYMENT_Cancelled = 'paiement_annulé';
}