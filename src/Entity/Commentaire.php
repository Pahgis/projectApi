<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\State\CommentaireProcessor;

#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\Post(
            processor: CommentaireProcessor::class,
            denormalizationContext: ['groups' => ['comment:create']],
            normalizationContext: ['groups' => ['comment:read']]
        ),
        new \ApiPlatform\Metadata\Patch(
            processor: CommentaireProcessor::class,
            denormalizationContext: ['groups' => ['comment:update']],
            normalizationContext: ['groups' => ['comment:read']],
            security: "is_granted('COMMENT_EDIT', object)"
        ),
        new \ApiPlatform\Metadata\Delete(
            security: "is_granted('COMMENT_DELETE', object)",
            output: false
        ),
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
    ],
    normalizationContext: ['groups' => ['comment:read']],
    denormalizationContext: ['groups' => ['comment:write']]
)]
#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['comment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['comment:read', 'comment:create', 'comment:update'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[Groups(['comment:read', 'comment:create'])]
    private ?Tache $tache = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
