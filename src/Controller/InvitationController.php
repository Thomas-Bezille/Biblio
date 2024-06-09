<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InvitationController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface $entityManager
    )
    {}

    #[Route('/invitation/{uuid}', name: 'app_invitation')]
    public function index(Invitation $invitation, Request $request, InvitationRepository $invitationRepository): Response
    {
        $invitation = $invitationRepository->findOneBy(['uuid' => $request->get('uuid')]);

        if ($invitation->getReader() !== null) {
            throw new \Exception("Cette invitation est déjà utilisée", 1);
            
        }

        $user = new User();
        $user->setEmail($invitation->getEmail());

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $invitation->setReader($user);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
