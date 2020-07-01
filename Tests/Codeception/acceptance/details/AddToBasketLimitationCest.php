<?php

namespace PaBlo\ArticleLimitPerOrder\Tests\Codeception\details;

use PaBlo\ArticleLimitPerOrder\Tests\Codeception\AcceptanceTester;
use PaBlo\ArticleLimitPerOrder\Tests\Codeception\Page\DetailsPage;

class AddToBasketLimitationCest
{
    /**
     * @param AcceptanceTester $I
     * @param DetailsPage $page
     * @throws \Exception
     */
    public function _before(AcceptanceTester $I, DetailsPage $page)
    {
        $I->amOnPage($page::route('&anid=adcb9deae73557006a8ac748f45288b4'));
        $I->waitForElement($page::$productHeadlineElement);
    }

    /**
     * @param AcceptanceTester $I
     * @param DetailsPage $page
     */
    public function basketButtonWillBeDisplayed(AcceptanceTester $I, DetailsPage $page)
    {
        $I->seeElement($page::$addToBasketButton);
    }

    /**
     * @param AcceptanceTester $I
     * @param DetailsPage $page
     * @throws \Exception
     */
    public function productCanBeAddedToTheBasketOnce(AcceptanceTester $I, DetailsPage $page)
    {
        $I->fillField($page::$productAmountField, 1);
        $I->click($page::$addToBasketButton);
        $I->waitForElementVisible($page::$basketModal);
        $I->see('1 Artikel im Warenkorb');
    }

    /**
     * @param AcceptanceTester $I
     * @param DetailsPage $page
     * @throws \Exception
     */
    public function productCanNotBeAddedTwiceToTheBasketOnce(AcceptanceTester $I, DetailsPage $page)
    {
        $I->fillField($page::$productAmountField, 2);
        $I->click($page::$addToBasketButton);
        $I->waitForElementVisible($page::$errorMessageContainer);
        $I->see('Die maximale Bestellmenge für den Artikel');
    }
}
