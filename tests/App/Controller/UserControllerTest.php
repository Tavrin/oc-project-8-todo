<?php

namespace Tests\App\Controller;

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

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'toto';
        $form['user[password][first]'] = 'tata';
        $form['user[password][second]'] = 'tata';
        $form['user[email]'] = 'tata@email.com';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateUserIsUser()
    {
        $this->userLogin();
        $this->client->request('GET', '/users/create');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUserIsAdmin()
    {
        $this->adminLogin();
        $crawler = $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Edit')->link();
        $crawler = $this->client->click($link);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'toto2';
        $form['user[password][first]'] = 'tata';
        $form['user[password][second]'] = 'tata';
        $form['user[email]'] = 'tata2@email.com';
        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUserIsUser()
    {
        $this->userLogin();
        $this->client->request('GET', '/users/0/edit');
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