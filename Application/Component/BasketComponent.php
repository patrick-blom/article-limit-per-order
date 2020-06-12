<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Application\Component;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use PaBlo\ArticleLimitPerOrder\Middleware\Validation\ArticleLimitation;
use Psr\Log\LoggerInterface;

/**
 * Class BasketComponent
 * @package PaBlo\ArticleLimitPerOrder\Component
 * @see \OxidEsales\Eshop\Application\Component\BasketComponent
 */
class BasketComponent extends BasketComponent_parent
{
    /**
     * {@inheritDoc}
     */
    public function toBasket($sProductId = null, $dAmount = null, $aSel = null, $aPersParam = null, $blOverride = false)
    {
        $container = ContainerFactory::getInstance()->getContainer();

        if (
            Registry::getSession()->getId() &&
            Registry::getSession()->isActualSidInCookie() &&
            !Registry::getSession()->checkSessionChallenge()
        ) {
            $container->get(LoggerInterface::class)->warning('EXCEPTION_NON_MATCHING_CSRF_TOKEN');
            Registry::getUtilsView()->addErrorToDisplay('ERROR_MESSAGE_NON_MATCHING_CSRF_TOKEN');

            return;
        }

        // adding to basket is not allowed ?
        if (Registry::getUtils()->isSearchEngine()) {
            return;
        }

        // validate
        if ($aProducts = $this->_getItems($sProductId, $dAmount, $aSel, $aPersParam, $blOverride)) {
            $basket = Registry::getSession()->getBasket();

            /**@var ArticleLimitation $validationService */
            $validationService = $container->get(ArticleLimitation::class);

            foreach ($aProducts as $productId => $aProduct) {
                if (!$validationService->validate($productId, (int)$aProduct['am'], $basket)) {
                    $article = oxNew(Article::class);
                    $article->load($productId);

                    $lang = Registry::getLang();
                    $errorMessage = sprintf(
                        $lang->translateString(
                            'PB_ERROR_MESSAGE_ARTICLE_LIMIT_REACHED_FOR_PRODUCT',
                            $lang->getTplLanguage()
                        ),
                        $article->getFieldData('oxtitle')
                    );
                    unset($article);

                    Registry::getUtilsView()->addErrorToDisplay($errorMessage);
                    return;
                }
            }
        }

        return parent::toBasket($sProductId, $dAmount, $aSel, $aPersParam, $blOverride);
    }
}
