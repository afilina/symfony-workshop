<?php
declare(strict_types=1);

namespace Tests\Acceptance\Context;

use Assert\Assert;
use Behat\Behat\Context\Context;
use Symfony\Component\BrowserKit\HttpBrowser;
use Tests\Acceptance\ApplicationState\ApplicationState;

class Product implements Context
{
    private HttpBrowser $browser;
    private ApplicationState $applicationState;

    public function __construct(HttpBrowser $browser, ApplicationState $applicationState)
    {
        $this->browser = $browser;
        $this->applicationState = $applicationState;
    }

    /**
     * @Given /^There is a product "([^"]*)"$/
     */
    public function thereIsAProduct(string $name): void
    {
        $this->applicationState->addProduct($name);
    }

    /**
     * @Given /^I am viewing product "([^"]*)"$/
     */
    public function iAmViewingProduct(string $name)
    {
        $product = $this->applicationState->getProduct($name);
        $this->browser->request('GET', '/products/' . $product['code']);
        Assert::that($this->browser->getResponse()->getStatusCode())
            ->eq(200);
    }

    /**
     * @When /^I list the products$/
     */
    public function iListTheProducts()
    {
        $this->browser->request('GET', '/products');
        Assert::that($this->browser->getResponse()->getStatusCode())
            ->eq(200);
    }

    /**
     * @Then /^I should see (\d+) products$/
     */
    public function iShouldSeeProducts(int $count)
    {
        Assert::that($this->browser->getCrawler()->filter('#products .product')->count())
            ->eq($count);
    }
}
