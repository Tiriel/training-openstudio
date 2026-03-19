<?php

namespace App\Controller;

use App\Constants\Roles;
use App\Constants\Votes;
use App\Entity\Conference;
use App\Form\Handler\ConferenceHandler;
use App\Form\Type\ConferenceType;
use App\Search\Interface\ConferenceSearchInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route('', name: 'app_conference_list', methods: ['GET'])]
    public function list(Request $request, ConferenceSearchInterface $search): Response
    {
        return $this->render('conference/list.html.twig', [
            'conferences' => $search->searchByName($request->query->get('name')),
        ]);
    }

    #[Route('/{id}', name: 'app_conference_show', requirements: ['id' => Requirement::UUID], methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    //#[IsGranted(new Expression("is_granted('ROLE_ORGANIZER') or is_granted('ROLE_WEBSITE')"))]
    #[Route('/new', name: 'app_conference_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_conference_edit', requirements: ['id' => Requirement::UUID], methods: ['GET', 'POST'])]
    public function newConference(?Conference $conference, Request $request, ConferenceHandler $handler): Response
    {
        if (null === $conference
            && (!$this->isGranted(Roles::ORGANIZER) && !$this->isGranted(Roles::WEBSITE))
        ) {
            throw $this->createAccessDeniedException('You do not have permission to create a conference.');
        }

        if ($conference instanceof Conference) {
            $this->denyAccessUnlessGranted(Votes::CONF_EDIT, $conference);
        }

        $conference ??= new Conference();

        return $handler->handle($conference, $request);
    }
}
