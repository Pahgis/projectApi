<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\State\TacheProcessor;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\Post(
            processor: TacheProcessor::class,
            denormalizationContext: ['groups' => ['tache:create']],
            normalizationContext: ['groups' => ['tache:read']]
        ),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Patch(
            processor: TacheProcessor::class,
            denormalizationContext: ['groups' => ['tache:update']],
            normalizationContext: ['groups' => ['tache:read']]
        ),
        new \ApiPlatform\Metadata\Delete(
            output: false // Suppression silencieuse
        ),
    ],
    normalizationContext: ['groups' => ['tache:read']],
    denormalizationContext: ['groups' => ['tache:write']]
)]
#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tache:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tache:read', 'tache:create', 'tache:update'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['tache:read', 'tache:create', 'tache:update'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['tache:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tache:read', 'tache:create', 'tache:update'])]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tache:read', 'tache:create', 'tache:update'])]
    private ?\DateTimeImmutable $datePreview = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tache:read'])]
    private ?string $priority = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tache:read'])]
    private ?string $status = null;

    #[ORM\Column]
    #[Groups(['tache:read'])]
    private ?bool $share = null;

    #[ORM\ManyToOne(inversedBy: 'tache')]
    #[Groups(['tache:create'])]
    private ?Liste $liste = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'tache')]
    private Collection $commentaires;

    #[Groups(['tache:create', 'tache:update'])]
    private ?array $emails = [];

    #[Groups(['tache:update'])]
    private ?array $removeEmails = [];

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'taches')]
    #[Groups(['tache:read'])]
    private Collection $membres;

    /**
     * @var Collection<int, SousTache>
     */
    #[ORM\OneToMany(targetEntity: SousTache::class, mappedBy: 'tache')]
    #[Groups(['tache:read'])]
    private Collection $sousTaches;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->priority = 'medium';
        $this->status = 'pending';
        $this->share = false;
        $this->commentaires = new ArrayCollection();
        $this->membres = new ArrayCollection();
        $this->sousTaches = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getDatePreview(): ?\DateTimeImmutable
    {
        return $this->datePreview;
    }

    public function setDatePreview(?\DateTimeImmutable $datePreview): static
    {
        $this->datePreview = $datePreview;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getListe(): ?Liste
    {
        return $this->liste;
    }

    public function setListe(?Liste $liste): static
    {
        $this->liste = $liste;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setTache($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getTache() === $this) {
                $commentaire->setTache(null);
            }
        }

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
     * @return Collection<int, User>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(User $membre): static
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
        }

        return $this;
    }

    public function removeMembre(User $membre): static
    {
        $this->membres->removeElement($membre);

        return $this;
    }

    /**
     * @return Collection<int, SousTache>
     */
    public function getSousTaches(): Collection
    {
        return $this->sousTaches;
    }

    public function addSousTach(SousTache $sousTach): static
    {
        if (!$this->sousTaches->contains($sousTach)) {
            $this->sousTaches->add($sousTach);
            $sousTach->setTache($this);
        }

        return $this;
    }

    public function removeSousTach(SousTache $sousTach): static
    {
        if ($this->sousTaches->removeElement($sousTach)) {
            // set the owning side to null (unless already changed)
            if ($sousTach->getTache() === $this) {
                $sousTach->setTache(null);
            }
        }

        return $this;
    }
}
