<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Projet;
use App\Entity\Sprint;
use App\Entity\Liste;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ProjetProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decoratedProcessor,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        
        
        if (!$data instanceof Projet) {
            return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
        }

        $user = $this->security->getUser();

        if ($user) {
            // Ajoutez l'utilisateur connecté comme membre du projet
            $data->addMember($user);
        }
        
        // Ajouter les membres à partir des emails
      
            if ($data->getEmails()) {
                foreach ($data->getEmails() as $email) {
                    $user = $this->userRepository->findOneBy(['email' => $email]);
                    
                    if ($user) {
                        $data->addMember($user);
                    }
                }
            }

            if ($data->getRemoveEmails()) {
                foreach ($data->getRemoveEmails() as $email) {
                    $user = $this->userRepository->findOneBy(['email' => $email]);
                    
                    if ($user) {
                        $data->removeMember($user);
                    }
                }
            }
            
            if (isset($context['request'])) {
                $request = $context['request'];
                $content = $request->getContent();
    
                if (!empty($content)) {
                    $decodedContent = json_decode($content, true);
    
                    if (isset($decodedContent['sprints']) && is_array($decodedContent['sprints'])) {
                        foreach ($decodedContent['sprints'] as $sprintData) {
                            $sprint = new Sprint();
                            $sprint->setName($sprintData['name'] ?? 'Sprint par défaut');
                            $sprint->setDescription($sprintData['description'] ?? null);
                            $sprint->setStartAt(isset($sprintData['startAt']) ? new \DateTimeImmutable($sprintData['startAt']) : null);
                            $sprint->setEndAt(isset($sprintData['endAt']) ? new \DateTimeImmutable($sprintData['endAt']) : null);
                            $sprint->setProject($data);

                            $backlog = new Liste();
                            $backlog->setName('Backlog');
                            $backlog->setSprint($sprint);

                            $sprint->addListe($backlog);
                            $data->addSprint($sprint);

                        }
                    }
                }

                if ($data->getSprints()->isEmpty()) {
                    $sprint = new Sprint();
                    $sprint->setName('Sprint Initial');
                    $sprint->setDescription('Sprint créé automatiquement pour ce projet.');
                    $sprint->setProject($data);

                    // Créer une liste "Backlog" pour ce sprint
                    $backlog = new Liste();
                    $backlog->setName('Backlog');
                    $backlog->setSprint($sprint);

                    $sprint->addListe($backlog);
                    $data->addSprint($sprint);
                }
            }

        // Persistance via le processeur décoré
        return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
    }
}
