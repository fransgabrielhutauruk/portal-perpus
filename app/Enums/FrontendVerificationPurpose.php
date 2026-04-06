<?php

namespace App\Enums;

enum FrontendVerificationPurpose: string
{
    case REQ_BUKU = 'req-buku';
    case REQ_MODUL = 'req-modul';
    case REQ_TURNITIN = 'req-turnitin';
    case REQ_BEBAS_PUSTAKA = 'req-bebas-pustaka';
}
