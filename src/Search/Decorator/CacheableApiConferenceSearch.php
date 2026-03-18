<?php

namespace App\Search\Decorator;

use App\Search\Interface\ConferenceSearchInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[When('prod')]
#[AsDecorator(ConferenceSearchInterface::class, priority: 5)]
class CacheableApiConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceSearchInterface $inner,
        private readonly CacheInterface $cache,
        private readonly SluggerInterface $slugger,
    ) {}

    public function searchByName(?string $name = null): array
    {
        $key = $this->slugger->slug($name ?? 'all-conferences')->lower()->toString();

        return $this->cache->get($key, function (CacheItem $item) use ($name) {
            $item
                ->expiresAfter(3600)
                ->set($this->inner->searchByName($name));

            return $item->get();
        });
    }
}
