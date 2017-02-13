<?php

namespace AppBundle\Messaging;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MessagingService implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendPasswordResetEmail($user) {
        $message = $this->container->get(Message::class);
        $message->setMessage('PasswordReset');
        $message->setTo($user->getEmail());
        $message->assign('user', $user);
        $message->renderBody();
        $this->mailer->send($message);
    }

}