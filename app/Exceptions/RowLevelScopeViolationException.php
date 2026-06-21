<?php

namespace App\Exceptions;

use Exception;

class RowLevelScopeViolationException extends Exception
{
    public function __construct(string $message = 'Akses ke data ini tidak diperbolehkan')
    {
        parent::__construct($message);
    }
}
