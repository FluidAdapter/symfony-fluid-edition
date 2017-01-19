<?php
namespace AppBundle\Security;

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
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param EncoderFactoryInterface $encoderFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EncoderFactoryInterface $encoderFactory,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->validator = $validator;
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


    public function encodePassword($user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $hashedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($hashedPassword);
    }
}
