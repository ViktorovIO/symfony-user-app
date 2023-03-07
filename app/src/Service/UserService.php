<?php

namespace App\Service;

use App\Entity\User as UserEntity;
use App\Message\Notification\SendEmailMessage;
use App\Model\User;
use App\Exception\UserSaveException;
use App\Repository\UserRepository;
use App\Transformer\UserTransformer;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;
    private ValidatorInterface $validator;
    private MessageBusInterface $eventBus;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        ValidatorInterface $validator,
        MessageBusInterface $eventBus
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->validator = $validator;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws UserSaveException
     */
    public function create(User $user): void
    {
        try {
            $email = $user->getEmail();
            if ($this->getByEmail($email) !== null) {
                throw new UserSaveException('User already exists');
            }

            $this->saveUser($user);
        } catch (Throwable $exception) {
            throw new UserSaveException('User save error: ' . $exception->getMessage());
        }

        $message = "Registered Successfully!\nYour password is: {$user->getPassword()}";
        $this->eventBus->dispatch(new SendEmailMessage($message, $email));
    }

    /**
     * @throws UserSaveException
     */
    public function update(User $user): void
    {
        try {
            $email = $user->getEmail();
            if ($this->getByEmail($email) === null) {
                throw new UserSaveException('User does not exists');
            }

            $this->saveUser($user);
        } catch (Throwable $exception) {
            throw new UserSaveException('User save error: ' . $exception->getMessage());
        }

        $message = "Updated Successfully!\nYour password is: {$user->getPassword()}";
        $this->eventBus->dispatch(new SendEmailMessage($message, $email));
    }

    public function getByEmail(string $email): ?UserEntity
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    private function saveUser(User $user): void
    {
        $userEntity = $this->userTransformer->transform($user);
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new UserSaveException((string) $errors);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($userEntity, $user->getPassword());
        $userEntity->setPassword($hashedPassword);

        $this->userRepository->save($userEntity, true);
    }
}
