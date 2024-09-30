<?php
namespace App\Enum;

enum statusResv: string
{
    case PENDING = 'En attente';
    case CONFIRM = 'Confirmé';
    case CANCELLED = 'Annulé';
}
