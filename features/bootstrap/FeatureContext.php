<?php

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Silex\Application;

/** @noinspection PhpIncludeInspection */
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
/** @noinspection PhpUndefinedClassInspection */
class FeatureContext extends MinkContext
{
    /**
     * @Then /^I wait$/
     */
    public function wait()
    {
        $this->getSession()->wait("20000");
    }
}
