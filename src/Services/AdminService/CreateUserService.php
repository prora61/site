<?php

namespace App\Services\AdminService;

use App\Entity\User;
use App\Exception\UserAlreadyExistsByEmailException;
use App\Model\CreateUserModel;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserService
{
    public function __construct(private readonly EntityManagerInterface $em,
                                private readonly UserPasswordHasherInterface $hasher,
                                private readonly UserRepository $userRepository)
    {
    }

    public function mapAndSave(CreateUserModel $createUserModel): void
    {
        if ($this->userRepository->existByEmail($createUserModel->getEmail())) {
            throw new UserAlreadyExistsByEmailException();
        }

        $user = new User();
        $user->setFirstName($createUserModel->getFirstName())
            ->setLastName($createUserModel->getLastName())
            ->setRoles(($createUserModel->isRoles() ? ['ROLE_ADMIN'] : ['ROLE_USER']))
            ->setEmail($createUserModel->getEmail());
        $user->setPassword($this->hasher->hashPassword($user, $createUserModel->getPassword()));

        $this->em->persist($user);
        $this->em->flush();
    }
}