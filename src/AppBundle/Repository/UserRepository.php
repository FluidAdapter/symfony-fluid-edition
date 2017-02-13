<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class UserRepository extends EntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function findOneByApiToken($apiToken)
    {
        $apiUser = $this->findOneBy(['apiToken' => $apiToken]);

        if (!$apiUser) {
            throw new AuthenticationCredentialsNotFoundException();
        }

        return $apiUser;
    }

    /**
     * @param string $email
     * @return null|User
     */
    public function findUserByEmail($email) {
        return $this->findOneBy(['email' => $email]);
    }
}
