<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Error\UnknownUserException;
use AppBundle\Messaging\MessagingService;
use AppBundle\Repository\UserRepository;
use AppBundle\Security\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManagerTest extends WebTestCase
{
    public function createUserManager($setupCallback = null)
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $encoderFactrory = $this->createMock(EncoderFactoryInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $userRepository = $this->createMock(UserRepository::class);
        $messagingService = $this->createMock(MessagingService::class);
        $userManager = new UserManager($entityManager, $encoderFactrory, $validator, $userRepository,
            $messagingService);
        if (is_callable($setupCallback)) {
            $setupCallback($entityManager, $encoderFactrory, $validator, $userRepository, $messagingService,
                $userManager);
        }

        return $userManager;
    }

    /**
     * @test
     */
    public function tryToResetOnNoneExistingUserTest()
    {
        $userManager = $this->createUserManager(function (
            $entityManager,
            $encoderFactrory,
            $validator,
            $userRepository,
            $messagingService,
            $userManager
        ) {
            $userRepository->expects($this->once())->method('findUserByEmail');
        });

        $this->expectException(UnknownUserException::class);
        $userManager->sendPasswordResetEmail('toni@mia3.com');
    }

    /**
     * @test
     */
    public function sendResetEmailTest()
    {
        $userManager = $this->createUserManager(function (
            $entityManager,
            $encoderFactrory,
            $validator,
            $userRepository,
            $messagingService,
            $userManager
        ) {
            $user = $this->createMock(User::class);
            $user->expects($this->once())->method('setSecurityToken');
            $entityManager->expects($this->once())->method('persist')->with($user);
            $entityManager->expects($this->once())->method('flush');
            $messagingService->expects($this->once())->method('sendPasswordResetEmail')->with($user);
            $userRepository->expects($this->once())->method('findUserByEmail')->willReturn($user);
        });

        $userManager->sendPasswordResetEmail('toni@mia3.com');
    }
}
