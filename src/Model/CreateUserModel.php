<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateUserModel
{
    #[NotBlank]
    private string $firstName;

    #[NotBlank]
    private string $lastName;

    #[Email]
    #[NotBlank]
    private string $email;

    #[NotBlank]
    #[Length(min: 8)]
    private string $password;

    private bool $roles;

    #[NotBlank]
    #[EqualTo(propertyPath: 'password', message: 'This value should be equal to password field')]
    private string $confirmPassword;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isRoles(): bool
    {
        return $this->roles;
    }

    public function setRoles(bool $roles): void
    {
        $this->roles = $roles;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }
}
