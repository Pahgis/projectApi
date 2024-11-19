<?php

namespace App\Entity;

use App\Repository\SprintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\Post(
            denormalizationContext: ['groups' => ['sprint:create']],
            normalizationContext: ['groups' => ['sprint:read']]
        ),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Patch(
            denormalizationContext: ['groups' => ['sprint:update']],
            normalizationContext: ['groups' => ['sprint:read']]
        ),
        new \ApiPlatform\Metadata\Delete()
    ],
    normalizationContext: ['groups' => ['sprint:read']],
    denormalizationContext: ['groups' => ['sprint:write']]
)]
#[ORM\Entity(repositoryClass: SprintRepository::class)]
class Sprint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['sprint:read', 'project:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['sprint:read', 'sprint:create', 'sprint:update', 'project:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['sprint:read', 'project:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['sprint:read', 'sprint:create', 'sprint:update'])]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['sprint:read', 'sprint:create', 'sprint:update'])]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['sprint:read', 'sprint:create', 'sprint:update'])]
    private ?\DateTimeImmutable $datePreviewEnd = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['sprint:read', 'sprint:create', 'sprint:update'])]    
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'sprints')]
    #[Groups(['sprint:read', 'project:create', 'sprint:create'])]
    private ?Projet $project = null;

    /**
     * @var Collection<int, Liste>
     */
    #[ORM\OneToMany(targetEntity: Liste::class, mappedBy: 'sprint')]
    private Collection $liste;

    public function __construct()
    {
        $this->liste = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getDatePreviewEnd(): ?\DateTimeImmutable
    {
        return $this->datePreviewEnd;
    }

    public function setDatePreviewEnd(?\DateTimeImmutable $datePreviewEnd): static
    {
        $this->datePreviewEnd = $datePreviewEnd;

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

    public function getProject(): ?Projet
    {
        return $this->project;
    }

    public function setProject(?Projet $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, Liste>
     */
    public function getListe(): Collection
    {
        return $this->liste;
    }

    public function addListe(Liste $liste): static
    {
        if (!$this->liste->contains($liste)) {
            $this->liste->add($liste);
            $liste->setSprint($this);
        }

        return $this;
    }

    public function removeListe(Liste $liste): static
    {
        if ($this->liste->removeElement($liste)) {
            // set the owning side to null (unless already changed)
            if ($liste->getSprint() === $this) {
                $liste->setSprint(null);
            }
        }

        return $this;
    }
}
