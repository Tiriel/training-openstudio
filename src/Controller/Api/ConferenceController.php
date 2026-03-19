<?php

namespace App\Controller\Api;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class ConferenceController extends AbstractController
{
    #[Route('/api/conference', name: 'app_api_conference')]
    public function index(Request $request, ConferenceRepository $repository): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $limit = 5;
        $conferences = $repository->findBy([], null, $limit, ($page - 1) * $limit);

        return $this->json(
            $conferences,
            context: [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn(object $o) => ['id' => $o->getId()],
                AbstractNormalizer::GROUPS => ['conference:list'],
            ]
        );
    }

    #[Route('/api/conference/{id}/edit', name: 'app_api_conference_edit', methods: ['PUT'])]
    public function edit(Request $request, Conference $conference, SerializerInterface $serializer, EntityManagerInterface $manager): JsonResponse
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Conference::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $conference,
        ]);
        $manager->flush();

        return $this->json([
            'message' => 'Conference updated successfully',
            'conference' => $conference,
        ], context: [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn(object $o) => ['id' => $o->getId()],
            AbstractNormalizer::GROUPS => ['conference:list'],
        ]);
    }
}
