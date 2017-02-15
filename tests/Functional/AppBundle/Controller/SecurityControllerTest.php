<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Security\UserManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function loginTest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/login');
        $this->assertEquals('{"message":"wrong username or password."}', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/api/login', [], [], [], json_encode([
            'username' => 'toni'
        ]));
        $this->assertEquals('{"message":"wrong username or password."}', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/api/login', [], [], [], json_encode([
            'username' => 'toni',
            'password' => 'tester'
        ]));
        $this->assertEquals('{"message":"logged in"}', $client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function resetPasswordTest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/reset-password');
        $this->assertEquals('{"message":"missing email."}', $client->getResponse()->getContent());

//        $crawler = $client->request('GET', '/api/reset-password', [], [], [], json_encode([
//            'email' => 'toni@mia3.com'
//        ]));
//        $this->assertEquals('{"message":"e-mail has been sent"}', $client->getResponse()->getContent());
    }
}
