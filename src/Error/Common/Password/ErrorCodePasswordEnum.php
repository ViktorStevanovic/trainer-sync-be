<?php

namespace App\Error\Common\Password;

final class ErrorCodePasswordEnum
{
    /**
     * La password vecchia non è corretta
     */
    public const ERROR_PASSWORD_001 = "ERROR_PASSWORD_001";

    /**
     * La password inserita non è conforme ai requisiti indicati
     */
    public const ERROR_PASSWORD_002 = "ERROR_PASSWORD_002";

    /**
     * La password contiene parole non permesse dal sistema
     */
    public const ERROR_PASSWORD_003 = "ERROR_PASSWORD_003";

    /**
     * La password inserita è uguale a una delle ultime 6 utilizzate
     */
    public const ERROR_PASSWORD_004 = "ERROR_PASSWORD_004";

    /**
     * L'utente non può cambiare password più di una volta ogni 24 ore
     */
    public const ERROR_PASSWORD_005 = "ERROR_PASSWORD_005";

    /**
     * Il token scaduto o non associato ad alcun utente
     */
    public const ERROR_PASSWORD_006 = "ERROR_PASSWORD_006";
}
