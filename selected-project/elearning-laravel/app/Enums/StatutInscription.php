<?php

namespace App\Enums;

enum StatutInscription: string
{
    case EN_COURS = 'EN_COURS';
    case TERMINEE = 'TERMINEE';
    case ABANDONNEE = 'ABANDONNEE';
}

