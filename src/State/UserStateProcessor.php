<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
class UserStateProcessor implements ProcessorInterface
{
    private $passwordHasher;
    private $security;
    private $userRepo;
    private $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, private ProcessorInterface $persistProcessor , Security $security,  UserRepository $userRepo , EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        $this->userRepo = $userRepo;
        $this->entityManager = $entityManager;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        
        if (!$data instanceof User) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }
        // Détecter si l'opération est une création ou une mise à jour
        $isCreation = $operation->getName() === '_api_/users/me_patch';
        
        if (!$isCreation) {
            $user = $this->handleUserCreation($data);
            return $this->persistProcessor->process($user, $operation, $uriVariables, $context);
        } else {
            $user = $this->handleUserUpdate($data);
            return $this->persistProcessor->process($user, $operation, $uriVariables, $context);
        }
        


         

        
    }

    private function handleUserCreation(User $user)
    {
        // Hachage du mot de passe lors de la création
        if ($user->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();
        }
        return $user;
    }

    private function handleUserUpdate(User $data)
    {
       
        // Obtenez l'utilisateur connecté
        $currentUser = $this->security->getUser();

        // Chargez l'utilisateur complet depuis le repository
        $user = $this->userRepo->findOneBy(['email' => $currentUser->getUserIdentifier()]);
        
        if (!$user) {
            throw new AccessDeniedHttpException('Utilisateur non trouvé.');
        }

        // Vérifiez que l'utilisateur modifie ses propres informations
        if (!$currentUser) {
            throw new AccessDeniedHttpException('Vous devez être connecté pour modifier vos informations.');
        }

        // Mise à jour du mot de passe si fourni
        if ($data->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPlainPassword());
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();
        }

        // Mise à jour de l'email si fourni
        if ($data->getEmail()) {
            $user->setEmail($data->getEmail());
        }

        return $user;
    }
}
