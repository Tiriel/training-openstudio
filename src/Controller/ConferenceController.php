<?php

namespace App\Controller;

use App\Constants\Roles;
use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Search\Client\ApiConferenceSearch;
use App\Search\Interface\ConferenceSearchInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function newConference(Request $request, EntityManagerInterface $manager): Response
    {
        if (!$this->isGranted(Roles::ORGANIZER) && !$this->isGranted(Roles::WEBSITE)) {
            throw $this->createAccessDeniedException('You do not have permission to create a conference.');
        }

        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);


        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($conference);
            $manager->flush();

            return $this->redirectToRoute('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('conference/new.html.twig', [
            'form' => $form,
        ]);
    }
}
