<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Projet;
use App\Entity\Sprint;
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
                $content = $request->getContent(); // Récupère le contenu brut JSON
                if (!empty($content)) {
                    $decodedContent = json_decode($content, true); // Transforme en tableau associatif
    
                    if (isset($decodedContent['initialSprint'])) {
                        $initialSprintData = $decodedContent['initialSprint'];
    
                        $sprint = new Sprint();
                        $sprint->setName($initialSprintData['name'] ?? 'Sprint par défaut');
                        $sprint->setDescription($initialSprintData['description'] ?? null);
                        $sprint->setProject($data);
                        $data->addSprint($sprint);
                    }
                }
            }

        // Persistance via le processeur décoré
        return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
    }
}
