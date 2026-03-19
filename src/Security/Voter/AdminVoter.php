<?php

namespace App\Security\Voter;

use App\Constants\Roles;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminVoter implements VoterInterface
{
    public function __construct(
        private readonly RoleHierarchyInterface $hierarchy,
    ) {}

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return self::ACCESS_ABSTAIN;
        }

        if (in_array(Roles::ADMIN, $this->hierarchy->getReachableRoleNames($user->getRoles()), true)) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
