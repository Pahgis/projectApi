<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Sprint;
use App\Entity\Liste;
use Doctrine\ORM\EntityManagerInterface;

class SprintProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decoratedProcessor,
        private EntityManagerInterface $entityManager
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Sprint) {
            return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
        }
        
        $backlog = new Liste();
        $backlog->setName('Backlog');
        $backlog->setSprint($data);

        // Ajouter la liste au sprint
        $data->addListe($backlog);

        return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
    }
}
