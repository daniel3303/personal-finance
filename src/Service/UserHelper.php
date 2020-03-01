<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-07-16
 * Time: 23:14
 */

namespace App\Service;


use App\Entity\User\User;
use App\Exception\UserResetPasswordException;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserHelper {

    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var string
     */
    private string $appName;

    public function __construct(MailerInterface $mailer,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator,
                                string $appName) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->appName = $appName;
    }

    /**
     * @param User $user
     * @return void True on success
     * @throws UserResetPasswordException
     */
    public function resetPassword(User $user): void {
        // set user password recover token and expiration
        try {
            $expiration = Carbon::now()->addDays(1);

            // Generate a random based UUID object
            $uuid4 = Uuid::uuid4();
            $token = $uuid4->toString() . "\n"; // i.e. 25769c6c-d34d-4bfe-ba98-e0ee856f3e7a

            $user->setPasswordTokenExpirationTime($expiration);
            $user->setPasswordToken($token);

            $this->entityManager->flush();


            $message = (new TemplatedEmail())
                ->subject($this->translator->trans('Recover your password') . ' | ' . $this->appName)
                ->to($user->getEmail())
                ->htmlTemplate('mails/user/recover_password.html.twig')
                ->context(['user' => $user]);

            $this->mailer->send($message);

        } catch (TransportExceptionInterface $e) {
            throw new UserResetPasswordException($this->translator->trans('Something unexpected happened! Try again later.'), $e);
        } catch (Exception $e) {
            throw new UserResetPasswordException($this->translator->trans('Something unexpected happened! Try again later.'), $e);
        }
    }
}