<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use App\Security\LoginFormAuthentificatorAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthentificatorAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_SUBSCRIBER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset_password", name="app_reset_password", requirements={"id"="\d+"})
     * @IsGranted("ROLE_SUBSCRIBER")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
        } catch (\ErrorException $e) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $request->request->get('reset_password')['oldPassword'];
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email

                return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
            }
            $form->addError(new FormError('Ancien mot de passe incorrect'));
        }

        return $this->render('security/reset_password_form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_SUBSCRIBER")
     */
    public function delete(Request $request, User $user): Response
    {
        try {
            /** @var User $userLogin */
            $userLogin = $this->getUser();

            if (!$this->isGranted('ROLE_ADMIN')) {
                $user = $userLogin;
            }
            if (\in_array('ROLE_ADMIN', $user->getRoles())) {
                throw new \LogicException("Impossible de supprimer un compte admin, il faut d'abord le passer en compte 'SUBSCRIBER'");
            }
        } catch (\LogicException $e) {
            // TODO redirect vers page pour modifier le ROLE
            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $session = new Session();
            $session->invalidate();
        }

        return $this->redirectToRoute('app_logout');
    }
}
