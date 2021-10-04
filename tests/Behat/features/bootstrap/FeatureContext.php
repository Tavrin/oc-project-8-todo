<?php

namespace Tests\Behat\features\bootstrap;

use App\Entity\Task;
use App\Entity\User;
use App\Kernel;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    public Session $session;

    /**
     * @var Kernel
     */
    private KernelInterface $kernel;

    public function __construct(Session $session, KernelInterface $kernel)
    {
        $this->session = $session;
        $this->kernel = $kernel;
    }

    /**
     * @Given I am an authenticated user
     *
     * @throws ElementNotFoundException
     */
    public function iAmAnAuthenticatedUser()
    {
        $user = new User();

        $this->session->visit('http://localhost:8000/login');
        $page = $this->session->getPage();
        $page->fillField('_username', 'user');
        $page->fillField('_password', 'root');
        $page->find('css', '.btn-success')->press();
    }

    /**
     * @When I create a task
     *
     * @throws ElementNotFoundException
     */
    public function iCreateATask()
    {
        $this->session->visit('http://localhost:8000/tasks/create');
        $page = $this->session->getPage();
        $page->fillField('task[title]', 'new behat task');
        $page->fillField('task[content]', 'new task');
        $button = $page->find('css', '.btn-success');
        $button->press();
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => 'user']);
        $task = new Task();
        $task->setTitle('new behat task');
        $task->setContent('new task');
        $task->setUser($user);

        $em->persist($task);
        $em->flush();
    }

    /**
     * @Then I should be associated to the task as its author
     */
    public function iShouldBeAssociatedToTheTaskAsItsAuthor()
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'new behat task']);
        'user' === $task->getUser()->getUsername();
    }
}
