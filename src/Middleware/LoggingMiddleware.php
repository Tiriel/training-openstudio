<?php

namespace App\Middleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class LoggingMiddleware implements MiddlewareInterface
{
    public function __construct(
        #[Target('console.logger')]
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
    ) {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $this->logger->info('New message handled', [
            'message' => $message::class,
            'payload' => $this->serializer->serialize($message, 'json'),
        ]);
        dump($message);

        return $stack->next()->handle($envelope, $stack);
    }
}
