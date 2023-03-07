<?php

namespace App\Model;

class User
{
    private ?int $id;
    private string $email;
    private string $personalNumber;
    private string $lastName;
    private string $firstName;
    private ?string $surname;
    private array $phoneList;
    private array $roles;
    private string $password;

    public function __construct(
        ?int $id,
        string $email,
        string $personalNumber,
        string $lastName,
        string $firstName,
        ?string $surname,
        array $phoneList,
        string $password,
        array $roles = []
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->personalNumber = $personalNumber;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->surname = $surname;
        $this->phoneList = $phoneList;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPersonalNumber(): string
    {
        return $this->personalNumber;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getPhoneList(): array
    {
        return $this->phoneList;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
