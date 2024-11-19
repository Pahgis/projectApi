<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\Post(
            denormalizationContext: ['groups' => ['liste:create']],
            normalizationContext: ['groups' => ['liste:read']]
        ),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Patch(
            denormalizationContext: ['groups' => ['liste:update']],
            normalizationContext: ['groups' => ['liste:read']]
        ),
        new \ApiPlatform\Metadata\Delete(
            output: false // Suppression silencieuse
        ),
    ],
    normalizationContext: ['groups' => ['liste:read']],
    denormalizationContext: ['groups' => ['liste:write']]
)]
#[ORM\Entity(repositoryClass: ListeRepository::class)]
class Liste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['liste:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['liste:read', 'liste:create', 'liste:update'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Tache>
     */
    #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'liste')]
    private Collection $tache;

    #[ORM\ManyToOne(inversedBy: 'liste')]
    #[Groups(['liste:read', 'liste:create'])]
    private ?Sprint $sprint = null;

    public function __construct()
    {
        $this->tache = new ArrayCollection();
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

    /**
     * @return Collection<int, Tache>
     */
    public function getTache(): Collection
    {
        return $this->tache;
    }

    public function addTache(Tache $tache): static
    {
        if (!$this->tache->contains($tache)) {
            $this->tache->add($tache);
            $tache->setListe($this);
        }

        return $this;
    }

    public function removeTache(Tache $tache): static
    {
        if ($this->tache->removeElement($tache)) {
            // set the owning side to null (unless already changed)
            if ($tache->getListe() === $this) {
                $tache->setListe(null);
            }
        }

        return $this;
    }


    public function getSprint(): ?Sprint
    {
        return $this->sprint;
    }

    public function setSprint(?Sprint $sprint): static
    {
        $this->sprint = $sprint;

        return $this;
    }
}
