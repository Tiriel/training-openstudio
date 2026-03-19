<?php

namespace App\Form\Handler;

use App\Entity\Conference;
use App\Form\Type\ConferenceType;
use App\Interface\CreateFormInterface;
use App\Interface\RedirectToRouteInterface;
use App\Interface\RenderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerHelper;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AutowireMethodOf;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConferenceHandler
{
    public function __construct(
        #[AutowireMethodOf(ControllerHelper::class)]
        private readonly RenderInterface $render,
        #[AutowireMethodOf(ControllerHelper::class)]
        private readonly CreateFormInterface $createForm,
        #[AutowireMethodOf(ControllerHelper::class)]
        private readonly RedirectToRouteInterface $redirectToRoute,
        private readonly EntityManagerInterface $manager,
        private readonly Security $security,
    ){}

    public function handle(Conference $conference, Request $request): Response
    {
        $form = $this->createForm(ConferenceType::class, $conference);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $conference->getCreatedBy()) {
                $conference->setCreatedBy($this->security->getUser());
            }
            $this->manager->persist($conference);
            $this->manager->flush();

            return ($this->redirectToRoute)('app_conference_show', ['id' => $conference->getId()]);
        }

        return ($this->render)('conference/new.html.twig', [
            'form' => $form,
        ]);
    }

    private function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return ($this->createForm)($type, $data, $options);
    }
}
