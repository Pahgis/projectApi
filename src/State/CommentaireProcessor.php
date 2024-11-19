<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Commentaire;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommentaireProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decoratedProcessor,
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Commentaire) {
            return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($operation->getMethod() === 'POST') {
            $this->handleCreate($data);
        }

        return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
    }

    private function handleCreate(Commentaire $commentaire): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new BadRequestHttpException('Vous devez être connecté pour commenter.');
        }

        $commentaire->setUser($user);
    }
}
