<?php

namespace App\MessageHandler;

use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly EntityManagerInterface $manager,
        #[AutowireIterator('app.matching_strategy')]
        private readonly iterable $strategies,
    ) {}

    public function __invoke(MatchVolunteerMessage $message): void
    {
        $user = $this->repository->find($message->userId);
        //dump($user);
    }
}
