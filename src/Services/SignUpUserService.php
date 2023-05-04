<?php

namespace App\Services;

use App\Entity\User;
use App\Model\SignUpModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignUpUserService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function mapAndSave(SignUpModel $signUpModel): void
    {
        $user = new User();
        $user->setFirstName($signUpModel->getFirstName())
            ->setLastName($signUpModel->getLastName())
            ->setRoles(['ROLE_USER'])
            ->setEmail($signUpModel->getEmail());
        $user->setPassword($this->hasher->hashPassword($user, $signUpModel->getPassword()));

        $this->em->persist($user);
        $this->em->flush();
    }
}
