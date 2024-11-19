<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\State\ProjetProcessor;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\Post(
            processor: ProjetProcessor::class,
            denormalizationContext: ['groups' => ['projet:create']],
            normalizationContext: ['groups' => ['projet:read']],
        ),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Patch(
            processor: ProjetProcessor::class,
            denormalizationContext: ['groups' => ['projet:update']],
            normalizationContext: ['groups' => ['projet:read']]
        ),
        new \ApiPlatform\Metadata\Delete(
            output: false // Indique que DELETE ne retourne aucun contenu
        ),
    ],
    normalizationContext: ['groups' => ['projet:read']],
    denormalizationContext: ['groups' => ['projet:write']]
)]
#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['projet:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['projet:read', 'projet:create', 'projet:update'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['projet:read' , 'projet:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column]
    private ?bool $share = false;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['projet:read', 'projet:create', 'projet:update'])]
    private ?string $description = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projets' , cascade: ['persist'])]
    #[Groups(['projet:read'])]
    private Collection $member;

    #[Groups(['projet:create', 'projet:update'])]
    private ?array $emails = [];

    #[Groups(['projet:update'])]
    private ?array $removeEmails = [];

    /**
     * @var Collection<int, Sprint>
     */
    #[ORM\OneToMany(targetEntity: Sprint::class, mappedBy: 'project' , cascade: ['persist', 'remove'])]
    #[Groups(['project:read', 'project:create'])]
    private Collection $sprints;

    #[Groups(['project:create'])]
    private ?array $initialSprint = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->share = false;
        $this->member = new ArrayCollection();
        $this->sprints = new ArrayCollection();
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

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function isShare(): ?bool
    {
        return $this->share;
    }

    public function setShare(bool $share): static
    {
        $this->share = $share;

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

    /**
     * @return Collection<int, User>
     */
    public function getMember(): Collection
    {
        return $this->member;
    }

    public function addMember(User $member): static
    {
        if (!$this->member->contains($member)) {
            $this->member->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        $this->member->removeElement($member);

        return $this;
    }

    public function getEmails(): ?array
    {
        return $this->emails;
    }

    public function setEmails(?array $emails): static
    {
        $this->emails = $emails;

        return $this;
    }

    public function getRemoveEmails(): ?array
    {
        return $this->removeEmails;
    }

    public function setRemoveEmails(?array $removeEmails): self
    {
        $this->removeEmails = $removeEmails;
        return $this;
    }

    /**
     * @return Collection<int, Sprint>
     */
    public function getSprints(): Collection
    {
        return $this->sprints;
    }

    public function addSprint(Sprint $sprint): static
    {
        if (!$this->sprints->contains($sprint)) {
            $this->sprints->add($sprint);
            $sprint->setProject($this);
        }

        return $this;
    }

    public function removeSprint(Sprint $sprint): static
    {
        if ($this->sprints->removeElement($sprint)) {
            // set the owning side to null (unless already changed)
            if ($sprint->getProject() === $this) {
                $sprint->setProject(null);
            }
        }

        return $this;
    }

    public function getInitialSprint(): ?array
    {
        return $this->initialSprint;
    }

    public function setInitialSprint(?array $initialSprint): static
    {
        $this->initialSprint = $initialSprint;

        return $this;
    }
}
