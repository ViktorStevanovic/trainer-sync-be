<?php

namespace App\Error\Common;

final class ErrorCodeEntityEnum
{
    /**
     * Impossibile gestire l'entità
     */
    public const ERROR_ENTITY_001 = "ERROR_ENTITY_001";

    /**
     * L’entità sta venendo gestita da qualcun altro. Riprova tra poco.
     */
    public const ERROR_ENTITY_002 = "ERROR_ENTITY_002";

    /**
     * Il valore non è univoco
     */
    public const ERROR_ENTITY_003 = "ERROR_ENTITY_003";
}
