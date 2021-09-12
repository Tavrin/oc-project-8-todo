<?php

namespace Tests\Behat;

use AppBundle\Entity\User;
use AppKernel;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\MinkExtension\Context\RawMinkContext;

class UserContext extends RawMinkContext implements Context
{
    private static $container;

    /**
     * @BeforeSuite
     */
    public static function bootstrapSymfony()
    {
        require_once __DIR__.'/../../app/autoload.php';
        require_once __DIR__.'/../../app/AppKernel.php';
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        self::$container = $kernel->getContainer();
    }

    /**
     * @Given /^I am an admin/
     * @throws ElementNotFoundException
     */
    public function iAmAnAdmin()
    {
        $this->getSession()->visit('http://localhost:8000/login');
        $page = $this->getSession()->getPage();
        $page->fillField('_username', 'admin');
        $page->fillField('_password', 'root');
        $page->find('css', '.btn-success')->press();
    }

    /**
     * @When I try to access a user's management page
     */
    public function iTryToAccessAnUserManagementPage()
    {
        $em = self::$container->get('doctrine')->getManager();
        $userId = $em->getRepository(User::class)->findOneBy(['username' => 'user']);
        $userId = $userId->getId();
        $this->getSession()->visit('http://localhost:8000/users/'.$userId.'/edit');
    }

    /**
     * @Then this access is granted
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function thisAccessIsGranted()
    {
        $this->assertSession()->statusCodeEquals(200);
    }

    /**
     * @Then this access is not granted
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function thisAccessIsNotGranted()
    {
        $this->assertSession()->statusCodeEquals(403);
    }
}