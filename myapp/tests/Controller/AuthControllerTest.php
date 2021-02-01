<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase {

    public function testLoginWithoutCredentials(): void
    {
        $client = static::createClient(['environment' => 'test', 'debug' => true]);
        $client->request('POST', '/api/login');


        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testLoginWithCredentials(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/login?email=wehner.jayce@schuster.com&password=1234',['email' => 'wehner.jayce@schuster.com' , 'password' => '1234']);
        print_r($client->getResponse()->getContent());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
