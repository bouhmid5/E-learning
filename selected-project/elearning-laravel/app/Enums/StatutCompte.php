<?php

namespace App\Enums;

enum StatutCompte: string
{
    case EN_ATTENTE = 'EN_ATTENTE';
    case ACTIF = 'ACTIF';
    case DESACTIVE = 'DESACTIVE';
    case REJETE = 'REJETE';
}

