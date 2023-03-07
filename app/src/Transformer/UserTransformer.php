<?php

namespace App\Transformer;

use App\Entity\User as UserEntity;
use App\Model\User;

class UserTransformer
{
    public function transform(User $user): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setId($user->getId());
        $userEntity->setEmail($user->getEmail());
        $userEntity->setPersonalNumber($user->getPersonalNumber());
        $userEntity->setLastName($user->getLastName());
        $userEntity->setFirstName($user->getFirstName());
        $userEntity->setSurname($user->getSurname());
        $userEntity->setPhoneList($user->getPhoneList());
        $userEntity->setPassword($user->getPassword());
        $userEntity->setRoles($user->getRoles());

        return $userEntity;
    }

    public function reverseTransform(UserEntity $userEntity): User
    {
        return new User(
            $userEntity->getId(),
            $userEntity->getEmail(),
            $userEntity->getPersonalNumber(),
            $userEntity->getLastName(),
            $userEntity->getFirstName(),
            $userEntity->getSurname(),
            $userEntity->getPhoneList(),
            $userEntity->getPassword(),
            $userEntity->getRoles()
        );
    }
}