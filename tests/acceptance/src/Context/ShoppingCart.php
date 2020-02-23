<?php
declare(strict_types=1);

namespace Tests\Acceptance\Context;

use Assert\Assert;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Tests\Acceptance\ApplicationState\ApplicationState;


class ShoppingCart implements Context
{
    private HttpBrowser $browser;
    private ApplicationState $applicationState;
    private array $formData;

    public function __construct(HttpBrowser $browser, ApplicationState $applicationState)
    {
        $this->browser = $browser;
        $this->applicationState = $applicationState;
    }

    /**
     * @When /^I add the product to my cart$/
     */
    public function iAddTheProductToMyCart()
    {
        $this->browser->submitForm('Add to Cart', []);
        $this->browser->followRedirect();
        Assert::that($this->browser->getResponse()->getStatusCode())
            ->eq(200);
    }

    /**
     * @Then /^I should see (\d+) products? in my cart$/
     */
    public function iShouldSeeProductInMyCart(int $count)
    {
        Assert::that($this->browser->getCrawler()->filter('#shopping_cart #products .product')->count())
            ->eq($count);
    }

    /**
     * @Then /^I should see that the quantity of "([^"]*)" is (\d+)$/
     */
    public function iShouldSeeThatTheQuantityOfProductIs(string $name, int $quantity)
    {
        $product = $this->browser
            ->getCrawler()
            ->filter('#shopping_cart #products .product')
            ->reduce(function (Crawler $crawler) use ($name) {
                return $crawler->filter('.product-name')->text() === $name;
            })->first();

        Assert::that($product->filter('.quantity')->text())
            ->eq($quantity);
    }
}
