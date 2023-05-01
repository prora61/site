<?php

namespace App\Services;

use App\Entity\User;
use App\Exception\UserAlreadyExistsByEmailException;
use App\Exception\UserNotFoundException;
use App\Model\CreateUserModel;
use App\Model\EditUserModel;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminService
{
    public function __construct(private readonly EntityManagerInterface      $em,
                                private readonly UserPasswordHasherInterface $hasher,
                                private readonly UserRepository              $userRepository)
    {
    }

    public function createUser(CreateUserModel $createUserModel): array
    {
        try {
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

            $response = [
                'success' => true,
            ];
        } catch (UserAlreadyExistsByEmailException $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        } catch (\Throwable $error) {
            //logger
            $response = [
                'success' => false,
                'message' => $error->getMessage(),
                'code' => $error->getCode()
            ];
        }

        return $response;
    }

    public function deleteUser(int $id): array
    {
        try {
            $user = $this->userRepository->getUserById($id);
            $this->userRepository->remove($user, true);
            $response = [
                'success' => true,
            ];
        } catch (UserNotFoundException $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        } catch (\Throwable $error) {
            //logger
            $response = [
                'success' => false,
                'message' => $error->getMessage(),
                'code' => $error->getCode()
            ];
        }

        return $response;
    }

    public function updateUser(Request $request): array
    {
        try {
            $id = $request->request->get('id');

            $user = $this->userRepository->getUserById($id);

            $user->setFirstName($request->request->get('firstName'))
                ->setLastName($request->request->get('lastName'))
                ->setEmail($request->request->get('email'));

            $this->em->persist($user);
            $this->em->flush();

            $response = [
                'success' => true,
            ];
        } catch (UserNotFoundException $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        } catch (\Throwable $error) {
            //logger
            $response = [
                'success' => false,
                'message' => $error->getMessage(),
                'code' => $error->getCode()
            ];
        }

        return $response;
    }

    public function getUser(int $id): EditUserModel
    {
        $user = $this->userRepository->getUserById($id);

        return (new EditUserModel())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setEmail($user->getEmail());
    }
}