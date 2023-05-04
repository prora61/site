<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDTOValidator extends ConstraintValidator
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueDTO) {
            throw new UnexpectedTypeException($constraint, UniqueDTO::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->userRepository->existByEmail($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
