<?php

namespace Tests\Behat\features\bootstrap;

use AppBundle\Entity\Task;
use AppKernel;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    private static $container;
    public $session;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }


    /**
     * @BeforeSuite
     */
    public static function bootstrapSymfony()
    {
        require_once __DIR__.'/../../../../app/autoload.php';
        require_once __DIR__.'/../../../../app/AppKernel.php';
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        self::$container = $kernel->getContainer();
    }

    /**
     * @Given I am an user
     * @throws ElementNotFoundException
     */
    public function iAmAnAuthenticatedUser()
    {
        $this->getSession()->visit('http://localhost:8000/login');
        $page = $this->getSession()->getPage();
        $page->fillField('_username', 'user');
        $page->fillField('_password', 'root');
        $page->find('css', '.btn-success')->press();
    }

    /**
     * @When i create a task
     * @throws ElementNotFoundException
     */
    public function iCreateATask()
    {
        $this->getSession()->visit('http://localhost:8000/tasks/create');
        $page = $this->getSession()->getPage();
        $page->fillField('task[title]', 'new behat task');
        $page->fillField('task[content]', 'new task');
        $button = $page->find('css', '.btn-success');
        $button->press();
    }

    /**
     * @Then I should be associated to the task as its author
     */
    public function iShouldBeAssociatedToTheTaskAsItsAuthor()
    {
        $em = self::$container->get('doctrine')->getManager();
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'new behat task']);
        $task->getUser()->getUsername() === 'user';
    }
}
