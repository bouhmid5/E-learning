<?php

namespace App\Enums;

enum StatutCours: string
{
    case BROUILLON = 'BROUILLON';
    case EN_ATTENTE_VALIDATION = 'EN_ATTENTE_VALIDATION';
    case PUBLIE = 'PUBLIE';
    case REJETE = 'REJETE';
    case ARCHIVE = 'ARCHIVE';
}

