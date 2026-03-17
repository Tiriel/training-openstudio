<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route('', name: 'app_conference_list', methods: ['GET'])]
    public function list(ConferenceRepository $repository): Response
    {
        return $this->render('conference/list.html.twig', [
            'conferences' => $repository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_conference_show', requirements: ['id' => Requirement::UUID], methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    #[Route('/new', name: 'app_conference_new')]
    public function newConference(): Response
    {
        $form = $this->createForm(ConferenceType::class);

        return $this->render('conference/new.html.twig', [
            'form' => $form,
        ]);
    }
}
