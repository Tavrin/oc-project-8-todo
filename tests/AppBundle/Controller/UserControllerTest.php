<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient([], [
            'HTTP_HOST' => 'localhost:8000',
        ]);
    }

    public function testCreateUserIsAdmin()
    {
        $this->adminLogin();
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateUserIsUser()
    {
        $this->userLogin();
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUserIsAdmin()
    {
        $this->adminLogin();
        $crawler = $this->client->request('GET', '/users/0/edit');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUserIsUser()
    {
        $this->userLogin();
        $crawler = $this->client->request('GET', '/users/0/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    private function userLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => 'user', '_password' => 'root']);
    }

    private function adminLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => 'admin', '_password' => 'root']);
    }
}