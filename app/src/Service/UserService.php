<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserSaveException;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function save(string $email, string $password, int $id = null): void
    {
        $user = new User($id, $email, $password);

        try {
            if ($this->getByEmail($email) !== null) {
                throw new UserSaveException('User already exists');
            }

            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $this->userRepository->save($user, true);
        } catch (Throwable $exception) {
            throw new UserSaveException('User save error: ' . $exception->getMessage());
        }
    }

    public function getByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
