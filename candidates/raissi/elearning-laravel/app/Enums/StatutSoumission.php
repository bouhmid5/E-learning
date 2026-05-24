<?php

namespace App\Enums;

enum StatutSoumission: string
{
    case SOUMISE = 'SOUMISE';
    case CORRIGEE = 'CORRIGEE';
    case REUSSIE = 'REUSSIE';
    case ECHOUEE = 'ECHOUEE';
}

