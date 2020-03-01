<?php

namespace App\Controller;

use App\Dto\User\RecoverPasswordData;
use App\Dto\User\ResetPasswordData;
use App\Exception\UserResetPasswordException;
use App\Form\User\RecoverPasswordType;
use App\Form\User\ResetPasswordType;
use App\Repository\User\UserRepository;
use App\Service\UserHelper;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController {

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }


    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/recover-password", name="recover_password", methods={"GET","POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserHelper $userHelper
     * @return Response
     */
    public function recoverPassword(Request $request, UserRepository $userRepository,  UserHelper $userHelper): Response {
        $model = new RecoverPasswordData();
        $form = $this->createForm(RecoverPasswordType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $model->getEmail()]);

            if($user === null){
                $this->addFlash('error', $this->translator->trans('No user was found with the email {{ email }}.', ['{{ email }}' => $model->getEmail()]));
            }else{
                try{
                    $userHelper->resetPassword($user);
                    $this->addFlash('success', $this->translator->trans('An email was sent with instructions to recover your password.'));
                }catch (UserResetPasswordException $e){
                    $this->addFlash('error', $this->translator->trans('An error occurred while resenting your password. Try again later or contact the support.'));
                }
            }

        }

        return $this->render('security/recover_password.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset-password/{email}/{token}", name="reset_password", methods={"GET","POST"})
     * @param string $email
     * @param string $token
     * @param Request $request
     * @param UserRepository $userRepository
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Exception
     */
    public function resetPassword(string $email, string $token, Request $request, UserRepository $userRepository, TranslatorInterface $translator): Response {
        $user = $userRepository->findOneBy(['email' => $email, 'passwordToken' => $token]);

        if($user === null || $user->getPasswordTokenExpirationTime() <= new DateTime()){
            $this->addFlash('warning', $translator->trans('Invalid password recover token.'));
            return $this->redirectToRoute('login');
        }

        $model = new ResetPasswordData();
        $form = $this->createForm(ResetPasswordType::class, $model);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPlainPassword($model->getNewPassword());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $translator->trans('Your password was successfully changed.'));
            return $this->redirectToRoute('login');
        }

        return $this->render('security/reset_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);


    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response {

        return $this->redirectToRoute('login');
    }
}
