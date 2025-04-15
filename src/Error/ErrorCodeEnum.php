<?php

namespace App\Error;

use App\Error\Common\ErrorCodeDefaultEnum;
use App\Error\Common\ErrorCodeEntityEnum;
use App\Error\Common\Password\ErrorCodePasswordEnum;

final class ErrorCodeEnum
{
    /**     
     * Dati in input mancanti o non validi     
     */
    public const ERROR_DEFAULT_ERROR_001 = ErrorCodeDefaultEnum::ERROR_DEFAULT_ERROR_001;

    /**     
     * Errore di comunicazione con il server     
     */
    public const ERROR_DEFAULT_ERROR_002 = ErrorCodeDefaultEnum::ERROR_DEFAULT_ERROR_002;

    /**     
     * Errore durante il salvataggio del file     
     */
    public const ERROR_DEFAULT_ERROR_003 = ErrorCodeDefaultEnum::ERROR_DEFAULT_ERROR_003;

    /**     
     * Risorsa non trovata     
     */
    public const ERROR_DEFAULT_ERROR_004 = ErrorCodeDefaultEnum::ERROR_DEFAULT_ERROR_004;

    /**     
     * Metodo HTTP non accettato     
     */
    public const ERROR_DEFAULT_ERROR_005 = ErrorCodeDefaultEnum::ERROR_DEFAULT_ERROR_005;

    /**     
     * Uno o più campi testo iniziano con caratteri non permessi     
     */
    public const ERROR_DEFAULT_ERROR_006 = ErrorCodeDefaultEnum::ERROR_DEFAULT_ERROR_006;

    /**     
     * Uno o più campi testo sono vuoti o assenti     
     */
    public const ERROR_DEFAULT_ERROR_007 = ErrorCodeDefaultEnum::ERROR_DEFAULT_ERROR_007;

    /**
     * Impossibile gestire l'entità
     */
    public const ERROR_ENTITY_001 = ErrorCodeEntityEnum::ERROR_ENTITY_001;

    /**
     * L’entità sta venendo gestita da qualcun altro. Riprova tra poco.
     */
    public const ERROR_ENTITY_002 = ErrorCodeEntityEnum::ERROR_ENTITY_002;

    /**
     * Il valore non è univoco
     */
    public const ERROR_ENTITY_003 = ErrorCodeEntityEnum::ERROR_ENTITY_003;

    /**     
     * La password vecchia non è corretta     
     */
    public const ERROR_PASSWORD_001 = ErrorCodePasswordEnum::ERROR_PASSWORD_001;

    /**     
     * La password inserita non è conforme ai requisiti indicati     
     */
    public const ERROR_PASSWORD_002 = ErrorCodePasswordEnum::ERROR_PASSWORD_002;

    /**     
     * La password contiene parole non permesse dal sistema     
     */
    public const ERROR_PASSWORD_003 = ErrorCodePasswordEnum::ERROR_PASSWORD_003;

    /**     
     * La password inserita è uguale a una delle ultime 6 utilizzate     
     */
    public const ERROR_PASSWORD_004 = ErrorCodePasswordEnum::ERROR_PASSWORD_004;

    /**     
     * L'utente non può cambiare password più di una volta ogni 24 ore     
     */
    public const ERROR_PASSWORD_005 = ErrorCodePasswordEnum::ERROR_PASSWORD_005;

    /**     
     * Il token scaduto o non associato ad alcun utente     
     */
    public const ERROR_PASSWORD_006 = ErrorCodePasswordEnum::ERROR_PASSWORD_006;
}
