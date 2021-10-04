<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient([], [
            'HTTP_HOST' => 'localhost:8000',
        ]);
    }

    public function testCreateAndEditTask()
    {
        $this->userLogin();
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'toto';
        $form['task[content]'] = 'tata';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $crawler = $this->client->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('toto')->link();
        $id = $link->getUri();
        preg_match('#/(\d.*)/#', $id, $id);
        $id = $id[1];
        $crawler = $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'toto2';
        $form['task[content]'] = 'tata2';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $this->client->request('GET', '/tasks/' . $id . '/toggle');
        $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $this->client->request('GET', '/tasks/' . $id . '/delete');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    private function userLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, ['_username' => 'user', '_password' => 'root']);
    }
}
