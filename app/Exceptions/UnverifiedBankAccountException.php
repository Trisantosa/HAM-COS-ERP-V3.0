<?php

namespace App\Exceptions;

use Exception;

class UnverifiedBankAccountException extends Exception
{
    public function __construct(string $message = 'Rekening bank belum terverifikasi')
    {
        parent::__construct($message);
    }
}
