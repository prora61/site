<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueDTO extends Constraint
{
    public string $message = 'A user with such email is already exists';
}