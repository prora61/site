<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Model\SignUpModel;
use App\Services\SignUpUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    public function __construct(private readonly SignUpUserService $signUpUser)
    {
    }

    #[Route(path: '/signup', name: 'signup')]
    public function register(Request $request): Response
    {
        $user = new SignUpModel();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->signUpUser->mapAndSave($user);
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ],
        );
    }
}
