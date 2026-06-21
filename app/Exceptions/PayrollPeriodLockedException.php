<?php

namespace App\Exceptions;

use Exception;

class PayrollPeriodLockedException extends Exception
{
    public function __construct(string $message = 'Periode payroll sudah terkunci')
    {
        parent::__construct($message);
    }
}
