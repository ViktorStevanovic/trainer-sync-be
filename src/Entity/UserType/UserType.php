<?php

namespace App\Entity\UserType;

use App\Repository\UserType\UserTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups as Serializer;

#[Entity(repositoryClass: UserTypeRepository::class)]
#[Table(name: 'user_types')]
class UserType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer(['userType'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Serializer(['userType'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING)]
    #[Serializer(['userType'])]
    private ?string $code = null;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
