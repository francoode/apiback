<?php

namespace ApiSecurityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testSecurityTokenAction_UserNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/security/token/aa/bb/cc');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testSecurityTokenAction_UserFound()
    {
        $client = static::createClient();

        $client->request('GET', '/security/token/admin0910/123456');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get('Content-Type'));
        $this->assertStringStartsWith('{"access_token', $client->getResponse()->getContent());
    }
}
