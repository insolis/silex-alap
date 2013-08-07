<?php

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Silex\Application;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Client;

/** @noinspection PhpIncludeInspection */
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
/** @noinspection PhpUndefinedClassInspection */
class FeatureContext extends BehatContext
{
    /** @var Application */
    protected $app;

    /** @var Client */
    protected $client;

    /** @var Crawler */
    protected $crawler;

    /**
     * @BeforeScenario
     */
    public function setup($event)
    {
        unset($this->app);

        /** @noinspection PhpIncludeInspection */
        $app = require "app/projektneve.php";
        $app["debug"] = true;
        $app["session.test"] = true;
        $app["exception_handler"]->disable();

        $this->app = $app;
        $this->client = new Client($app);
        $this->client->followRedirects(false);
    }

    /**
     * @Given /^(|nem )lájkoltam az oldalt$/
     */
    public function lajkoltamAzOldalt($nem)
    {
        if ($nem === "nem ") {
            $this->app["session"]->set("fb.page_liked", false);
        } else {
            $this->app["session"]->set("fb.page_liked", true);
        }
    }

    /**
     * @When /^a kezdőlapra megyek$/
     */
    public function aKezdolapraMegyek()
    {
        $this->crawler = $this->client->request("GET", "/");
    }

    /**
     * @Then /^azt látom, hogy "([^"]*)"$/
     */
    public function aztLatomHogy($szoveg)
    {
        assertContains($szoveg, $this->client->getResponse()->getContent());
    }

}
