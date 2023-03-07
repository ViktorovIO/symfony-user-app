<?php

namespace App\Service;

use App\Entity\User as UserEntity;
use App\Message\Notification\SendEmailMessage;
use App\Model\User;
use App\Exception\UserSaveException;
use App\Repository\UserRepository;
use App\Transformer\UserTransformer;
use Psr\Log\LoggerInterface;
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
    private LoggerInterface $logger;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        ValidatorInterface $validator,
        MessageBusInterface $eventBus,
        LoggerInterface $logger
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->validator = $validator;
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function findAll(): array
    {
        $this->logger->info('Get all users');

        return $this->userRepository->findAll();
    }

    public function searchByQuery(array $searchQuery)
    {
        $logMessage = 'Search by query: ';
        foreach ($searchQuery as $key => $value) {
            $logMessage .= "$key -> $value, ";
        }

        $this->logger->info($logMessage);

        return $this->userRepository->searchByQuery($searchQuery);
    }

    /**
     * @throws UserSaveException
     */
    public function create(User $user): void
    {
        $this->logger->info('Start User create');

        try {
            $email = $user->getEmail();
            if ($this->getByEmail($email) !== null) {
                throw new UserSaveException('User already exists');
            }

            $this->saveUser($user);
        } catch (Throwable $exception) {
            $message = 'User create error: ' . $exception->getMessage();
            $this->logger->error($message);

            throw new UserSaveException($message);
        }

        $message = "Registered Successfully!\nYour password is: {$user->getPassword()}";
        $this->eventBus->dispatch(new SendEmailMessage($message, $email));

        $this->logger->info('User registered successfully');
    }

    /**
     * @throws UserSaveException
     */
    public function update(User $user): void
    {
        $this->logger->info('Start User update');

        try {
            $email = $user->getEmail();
            if ($this->getByEmail($email) === null) {
                throw new UserSaveException('User does not exists');
            }

            $this->saveUser($user);
        } catch (Throwable $exception) {
            $message = 'User update error: ' . $exception->getMessage();
            $this->logger->error($message);

            throw new UserSaveException($message);
        }

        $message = "Updated Successfully!\nYour password is: {$user->getPassword()}";
        $this->eventBus->dispatch(new SendEmailMessage($message, $email));

        $this->logger->info('User updated successfully');
    }

    public function delete(User $user): void
    {
        $this->logger->info('Start User delete');

        $this->userRepository->remove($this->userTransformer->transform($user), true);

        $this->logger->info('User deleted successfully');
    }

    private function getByEmail(string $email): ?UserEntity
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
