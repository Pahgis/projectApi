<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
class UserStateProcessor implements ProcessorInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher, private ProcessorInterface $persistProcessor)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof User && $data->getPlainPassword()) {
            $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPlainPassword()));
            $data->eraseCredentials();
        }
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
