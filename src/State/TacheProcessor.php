<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Projet;
use App\Entity\Tache;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class TacheProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $decoratedProcessor,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
 
        if (!$data instanceof Tache) {
            return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
        }

        
        $liste = $data->getListe();
        $sprint = $liste->getSprint();
        $projet = $sprint->getProjet();

        // Ajouter les membres à partir des emails
        
            if ($data->getEmails()) {
                foreach ($data->getEmails() as $email) {
                    $user = $this->userRepository->findOneBy(['email' => $email]);
                    foreach ($projet->getMember() as $members) {
                        if ($user === $members) {
                            $data->addMembre($user);
                        }
                    }
                    
                }
            }

            if ($data->getRemoveEmails()) {
                foreach ($data->getRemoveEmails() as $email) {
                    $user = $this->userRepository->findOneBy(['email' => $email]);
                    
                    if ($user) {
                        $data->removeMembre($user);
                    }
                }
            }
        


        // Persistance via le processeur décoré
        return $this->decoratedProcessor->process($data, $operation, $uriVariables, $context);
    }
}
