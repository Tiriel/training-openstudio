<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Volunteering;
use App\Form\Type\VolunteeringType;
use App\Message\CreateVolunteerCommand;
use App\Middleware\Stamp\PriorityStamp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/volunteering')]
final class VolunteeringController extends AbstractController
{
    #[Route('/new', name: 'app_volunteering_new', methods: ['GET', 'POST'])]
    public function newVolunteering(
        Request $request,
        EntityManagerInterface $manager,
        #[Target('command.bus')] MessageBusInterface $bus
    ): Response {
        $message = (new CreateVolunteerCommand($this->getUser()->getId()))
        ;

        if ($request->query->has('conference')) {
            $conference = $manager->getRepository(Conference::class)->find($request->query->get('conference'));
            $message->conferenceId = $conference->getId();
        }

        $form = $this->createForm(VolunteeringType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bus->dispatch($message, [new PriorityStamp(10)]);

            return $this->redirectToRoute('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('volunteering/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_volunteering_show', methods: ['GET'])]
    public function show(Volunteering $volunteering): Response
    {
        return $this->render('volunteering/show.html.twig', [
            'volunteering' => $volunteering,
        ]);
    }
}
