<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use App\Entity\User;

class AdminVoter extends Voter
{
    const ADMIN = 'ROLE_ADMIN';
    const CLIENT_USER = 'ROLE_CLIENT';
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'VIEW', 'LIST'])
            && ($subject instanceof User);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array(self::ADMIN, $user->getRoles())){
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                  return $this->canEdit($subject, $user);
            case 'VIEW':
                  return $this->canView($subject, $user);
            case 'LIST':
                  return $this->canList($token);
        }

        return false;
    }

    private function canEdit(TokenInterface $token): bool
    {
        if ($this->decisionManager->decide($token, array(self::ADMIN))) {
            return true;
        }

        return false;
    }

    private function canView(TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array(self::ADMIN))) {
            return true;
        }

        return false;
    }

    private function canList(TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array(self::ADMIN))) {
            return true;
        }

        return false;
    }
}
