<?php

namespace App\Services;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\SignUpModel;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignUpUser
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepository $userRepository)
    {
    }

    public function mapAndSave(SignUpModel $signUpModel): void
    {
        if ($this->userRepository->existByEmail($signUpModel->getEmail())) {
            throw new UserAlreadyExistsException();
        }

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
