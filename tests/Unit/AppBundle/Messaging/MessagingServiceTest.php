<?php

namespace Tests\AppBundle\Messaging;

use AppBundle\Entity\User;
use AppBundle\Messaging\Message;
use AppBundle\Messaging\MessagingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TYPO3Fluid\Fluid\View\TemplateView;

class MessagingServiceTest extends KernelTestCase
{
    /**
     * @test
     */
    public function sendPasswordResetEmail()
    {
        $user = new User();
        $user->setEmail('toni@mia3.com');

        $message = $this->createMock(Message::class);
        $message->expects($this->once())->method('setMessage')->with('PasswordReset');
        $message->expects($this->once())->method('setTo')->with($user->getEmail());
        $message->expects($this->once())->method('assign')->with('user', $user);
        $message->expects($this->once())->method('renderBody');

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())->method('get')->with(Message::class)->willReturn($message);

        $mailer = $this->createMock(\Swift_Mailer::class);
        $mailer->expects($this->once())->method('send')->with($message);

        $messagingService = new MessagingService($mailer);
        $messagingService->setContainer($container);
        $messagingService->sendPasswordResetEmail($user);
    }
}