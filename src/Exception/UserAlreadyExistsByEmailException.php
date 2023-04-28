<?php

namespace App\Exception;

class UserAlreadyExistsByEmailException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('This email adress is already in use.');
    }
}
