<?php

namespace App\MessageHandler;

use App\Message\MatchVolunteerMessage;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class MatchVolunteerMessageHandler
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {}

    public function __invoke(MatchVolunteerMessage $message): void
    {
        $user = $this->repository->find($message->userId);
        dump($user);
    }
}
