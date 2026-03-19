<?php

namespace App\Security\Voter;

use App\Constants\Roles;
use App\Constants\Votes;
use App\Entity\Conference;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EditConferenceVoter implements VoterInterface
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
    ) {}

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        foreach ($attributes as $attribute) {
            if ($attribute !== Votes::CONF_EDIT || !$subject instanceof Conference) {
                return self::ACCESS_ABSTAIN;
            }
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        return (
            $user === $subject->getCreatedBy()
                || \count(\array_intersect(
                        $subject->getOrganizations()->toArray(),
                        $user->getOrganizations()->toArray()
                    )) > 0
                || $this->checker->isGranted(Roles::WEBSITE)
        )
            ? self::ACCESS_GRANTED
            : self::ACCESS_DENIED;
    }
}
