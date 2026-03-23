<?php

namespace App\Controller;

use App\Dto\Registration;
use App\Entity\User;
use App\Entity\VolunteerProfile;
use App\Form\Type\AccountFormType;
use App\Form\Type\RegistrationFormType;
use App\Message\MatchVolunteerMessage;
use App\Middleware\Stamp\PriorityStamp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
    ): Response {
        $registration = new Registration();
        $form = $this->createForm(RegistrationFormType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isFinished() && $form->isValid()) {
            $user = $registration->toEntity();
            $user
                ->setApikey()
                ->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $bus->dispatch(new MatchVolunteerMessage($user->getId()), [new PriorityStamp(10)]);

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->getStepForm(),
        ]);
    }
}
