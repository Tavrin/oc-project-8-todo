<?php

namespace Tests\Behat;

use App\Entity\User;
use App\Kernel;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

class UserContext extends RawMinkContext implements Context
{
    /**
     * @var Kernel
     */
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I am an admin/
     *
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
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $userId = $em->getRepository(User::class)->findOneBy(['username' => 'user']);
        $userId = $userId->getId();
        $this->getSession()->visit('http://localhost:8000/users/' . $userId . '/edit');
    }

    /**
     * @Then this access is granted
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function thisAccessIsGranted()
    {
        $this->assertSession()->statusCodeEquals(404);
    }

    /**
     * @Then this access is not granted
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function thisAccessIsNotGranted()
    {
        $this->assertSession()->statusCodeEquals(403);
    }
}
