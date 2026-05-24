<?php

namespace App\Enums;

enum TypeQuestion: string
{
    case QCM = 'QCM';
    case VRAI_FAUX = 'VRAI_FAUX';
    case REPONSE_COURTE = 'REPONSE_COURTE';
    case NUMERIQUE = 'NUMERIQUE';
}

