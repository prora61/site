<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueDTO extends Constraint
{
    public string $message = 'A user with "{{ string }}" is already exists';

    public string $email;

    public function getTargets(): string
    {
        return parent::CLASS_CONSTRAINT;
    }
}