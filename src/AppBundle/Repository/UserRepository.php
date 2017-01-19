<?php

namespace AppBundle\Repository;

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
}
