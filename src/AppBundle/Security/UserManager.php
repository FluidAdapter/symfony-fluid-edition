<?php

namespace AppBundle\Security;

use AppBundle\Error\UnknownUserException;
use AppBundle\Messaging\MessagingService;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var MessagingService
     */
    protected $messagingService;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param EncoderFactoryInterface $encoderFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EncoderFactoryInterface $encoderFactory,
        ValidatorInterface $validator,
        UserRepository $userRepository,
        MessagingService $messagingService
    ) {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->messagingService = $messagingService;
    }

    /**
     *
     * @param $username
     * @param $email
     * @param $password
     * @param bool $enabled
     * @param bool $admin
     * @return User
     * @throws ResourceValidationError
     */
    public function createUser($username, $email, $password, $enabled = true, $admin = false)
    {
        $user = new User();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setActive((bool)$enabled);
        $user->setAdmin((bool)$admin);

        $this->encodePassword($user, $password);

        $validationResults = $this->validator->validate($user);
        if ($validationResults->count() > 0) {
            throw new ResourceValidationError('User failed to validate', 1481206495, $validationResults);
        }

        if ($admin === true) {
            $user->setRoles(['ROLE_ADMIN']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        $user->setApiToken(bin2hex(random_bytes(24)));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }


    /**
     * @param string $user
     * @param string $password
     */
    public function encodePassword($user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $hashedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($hashedPassword);
    }

    /**
     * @param string $email
     * @throws UnknownUserException
     */
    public function sendPasswordResetEmail($email)
    {
        $user = $this->userRepository->findUserByEmail($email);
        if ($user === null) {
            throw new UnknownUserException('Unknown E-Mail');
        }
        $user->setSecurityTOken(bin2hex(random_bytes(24)));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->messagingService->sendPasswordResetEmail($user);
    }
}
