<?php

namespace App\Error\Common;

final class ErrorCodeDefaultEnum
{
    /**
     * Dati in input mancanti o non validi
     */
    public const ERROR_DEFAULT_ERROR_001 = "ERROR_DEFAULT_ERROR_001";

    /**
     * Errore di comunicazione con il server
     */
    public const ERROR_DEFAULT_ERROR_002 = "ERROR_DEFAULT_ERROR_002";

    /**
     * Errore durante il salvataggio del file
     */
    public const ERROR_DEFAULT_ERROR_003 = "ERROR_DEFAULT_ERROR_003";

    /**
     * Risorsa non trovata
     */
    public const ERROR_DEFAULT_ERROR_004 = "ERROR_DEFAULT_ERROR_004";

    /**
     * Metodo HTTP non accettato
     */
    public const ERROR_DEFAULT_ERROR_005 = "ERROR_DEFAULT_ERROR_005";

    /**
     * Uno o più campi testo iniziano con caratteri non permessi
     */
    public const ERROR_DEFAULT_ERROR_006 = "ERROR_DEFAULT_ERROR_006";

    /**
     * Uno o più campi testo sono vuoti o assenti
     */
    public const ERROR_DEFAULT_ERROR_007 = "ERROR_DEFAULT_ERROR_007";

    /**
     * ReCaptcha non valido
     */
    public const ERROR_DEFAULT_ERROR_008 = "ERROR_DEFAULT_ERROR_008";
}
