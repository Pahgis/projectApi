<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CommentaireVoter extends Voter
{
    public const EDIT = 'COMMENT_EDIT';
    public const DELETE = 'COMMENT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Commentaire;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $commentaire = $subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // Seul le propriétaire du commentaire peut le modifier
                return $commentaire->getUser() === $user;

            case self::DELETE:
                // Par exemple, seul le propriétaire peut voir un commentaire spécifique
                return $commentaire->getUser() === $user;
        }

        return false;   
    }
}
