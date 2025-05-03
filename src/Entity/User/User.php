<?php

namespace App\Entity\User;

use App\Entity\Trainer\Trainer;
use App\Entity\UserType\UserType;
use App\Enum\UserType\UserTypeEnum;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\Table('users')]
#[ORM\Index(name: 'idx_email', fields: ['email'])]
#[ORM\Index(name: 'idx_phone', fields: ['phone'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 180, nullable: false)]
    #[Serializer\Groups(['minimalUser', 'simpleUser', 'user'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 180, nullable: false)]
    #[Serializer\Groups(['minimalUser', 'simpleUser', 'user'])]
    private ?string $surname = null;

    #[ORM\Column(type: Types::STRING, length: 180, nullable: false)]
    #[Serializer\Groups(['minimalUser', 'simpleUser', 'user'])]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Serializer\Groups(['minimalUser', 'simpleUser', 'user'])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::STRING, options: ['default' => 'tmp-password'])]
    private ?string $password = 'tmp-password';

    #[ORM\ManyToOne(targetEntity: UserType::class)]
    #[JoinColumn(nullable: false)]
    #[Serializer\Groups(['minimalUser', 'simpleUser', 'user'])]
    private ?UserType $userType = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $lastToken = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $confirmationToken = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $resetPasswordToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['simpleUser', 'user'])]
    private ?DateTime $resetPasswordRequestedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['simpleUser', 'user'])]
    private ?DateTime $passwordChangedAt = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    #[Serializer\Groups(['minimalUser', 'simpleUser', 'user'])]
    private ?bool $active = true;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    #[Serializer\Groups(['user'])]
    private ?bool $confirmedEmail = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['user'])]
    private ?DateTime $confirmedEmailAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: true)]
    #[Serializer\Groups(['simpleUser', 'user'])]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['simpleUser', 'user'])]
    private ?DateTime $createdAt = null;


    # ===============================
    # ===== Other methods
    # ===============================

    public function isAdmin(): ?bool
    {
        return $this->getUserType()->getCode() === UserTypeEnum::ADMIN;
    }

    public function isTrainer(): ?bool
    {
        return $this->getUserType()->getCode() === UserTypeEnum::TRAINER;
    }

    public function isClient(): ?bool
    {
        return $this->getUserType()->getCode() === UserTypeEnum::CLIENT;
    }

    # ===============================
    # ===== Getters & Setters
    # ===============================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        return $roles;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLastToken(): ?string
    {
        return $this->lastToken;
    }

    public function setLastToken(?string $lastToken): static
    {
        $this->lastToken = $lastToken;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function isConfirmedEmail(): ?bool
    {
        return $this->confirmedEmail;
    }

    public function setConfirmedEmail(bool $confirmedEmail): static
    {
        $this->confirmedEmail = $confirmedEmail;

        return $this;
    }

    public function getConfirmedEmailAt(): ?\DateTimeInterface
    {
        return $this->confirmedEmailAt;
    }

    public function setConfirmedEmailAt(?\DateTimeInterface $confirmedEmailAt): static
    {
        $this->confirmedEmailAt = $confirmedEmailAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserType(): ?UserType
    {
        return $this->userType;
    }

    public function setUserType(?UserType $userType): static
    {
        $this->userType = $userType;

        return $this;
    }

    public function getCreatedBy(): ?self
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?self $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the value of confirmationToken
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * Set the value of confirmationToken
     */
    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Get the value of passwordChangedAt
     */
    public function getPasswordChangedAt(): ?DateTime
    {
        return $this->passwordChangedAt;
    }

    /**
     * Set the value of passwordChangedAt
     */
    public function setPasswordChangedAt(?DateTime $passwordChangedAt): self
    {
        $this->passwordChangedAt = $passwordChangedAt;

        return $this;
    }

    /**
     * Get the value of resetPasswordToken
     */
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    /**
     * Set the value of resetPasswordToken
     */
    public function setResetPasswordToken(?string $resetPasswordToken): self
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    /**
     * Get the value of resetPasswordRequestedAt
     */
    public function getResetPasswordRequestedAt(): ?DateTime
    {
        return $this->resetPasswordRequestedAt;
    }

    /**
     * Set the value of resetPasswordRequestedAt
     */
    public function setResetPasswordRequestedAt(?DateTime $resetPasswordRequestedAt): self
    {
        $this->resetPasswordRequestedAt = $resetPasswordRequestedAt;

        return $this;
    }

    /**
     * Get the value of phone
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
