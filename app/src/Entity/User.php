<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.',)]
    #[Assert\NotBlank(message: 'Email field is can\'t be empty')]
    private string $email;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Personal Number field is can\'t be empty')]
    private string $personalNumber;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Last Name field is can\'t be empty')]
    private string $lastName;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'First Name field is can\'t be empty')]
    private string $firstName;

    #[ORM\Column(length: 180)]
    private ?string $surname = null;

    #[ORM\Column]
    private array $phoneList;

    #[ORM\Column]
    private array $roles;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Password field is can\'t be empty')]
    private string $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPersonalNumber(): string
    {
        return $this->personalNumber;
    }

    public function setPersonalNumber(string $personalNumber): void
    {
        $this->personalNumber = $personalNumber;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getPhoneList(): array
    {
        return $this->phoneList;
    }

    public function setPhoneList(array $phoneList): void
    {
        $this->phoneList = $phoneList;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
