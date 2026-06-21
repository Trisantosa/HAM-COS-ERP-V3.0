<?php

namespace App\Exceptions;

use Exception;

class CertificationRequiredException extends Exception
{
    public function __construct(string $message = 'Sertifikasi diperlukan untuk operasi ini')
    {
        parent::__construct($message);
    }
}
