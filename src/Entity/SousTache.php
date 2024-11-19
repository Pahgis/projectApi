<?php

namespace App\Entity;

use App\Repository\SousTacheRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\Post(
            denormalizationContext: ['groups' => ['soustache:create']],
            normalizationContext: ['groups' => ['soustache:read']]
        ),
        new \ApiPlatform\Metadata\Patch(
            denormalizationContext: ['groups' => ['soustache:update']],
            normalizationContext: ['groups' => ['soustache:read']]
        ),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Delete()
    ],
    normalizationContext: ['groups' => ['soustache:read']],
    denormalizationContext: ['groups' => ['soustache:write']]
)]
#[ORM\Entity(repositoryClass: SousTacheRepository::class)]
class SousTache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['soustache:read', 'tache:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['soustache:read', 'soustache:create', 'soustache:update', 'tache:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['soustache:read', 'soustache:create', 'soustache:update', 'tache:read'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'sousTaches')]
    #[Groups(['soustache:read', 'soustache:create'])]
    private ?Tache $tache = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): static
    {
        $this->tache = $tache;

        return $this;
    }
}
