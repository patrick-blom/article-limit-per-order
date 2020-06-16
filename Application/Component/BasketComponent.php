<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Application\Component;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use PaBlo\ArticleLimitPerOrder\Adapter\Validation\ArticleLimitation;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BasketComponent
 * @package PaBlo\ArticleLimitPerOrder\Component
 * @see \OxidEsales\Eshop\Application\Component\BasketComponent
 * @mixin \OxidEsales\Eshop\Application\Component\BasketComponent
 */

class BasketComponent extends BasketComponent_parent
{
    /**
     * {@inheritDoc}
     */
    public function toBasket($sProductId = null, $dAmount = null, $aSel = null, $aPersParam = null, $blOverride = false)
    {
        $container = $this->getDIContainer();

        if (null === $container) {
            throw new StandardException('Could not create service container');
        }

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
            if(false === $this->validateBasketItemAppending($aProducts)){
                return;
            }
        }

        return parent::toBasket($sProductId, $dAmount, $aSel, $aPersParam, $blOverride);
    }

    /**
     * {@inheritDoc}
     */
    public function changeBasket(
        $sProductId = null,
        $dAmount = null,
        $aSel = null,
        $aPersParam = null,
        $blOverride = true
    ) {
        if (!Registry::getSession()->checkSessionChallenge()) {
            return;
        }

        // adding to basket is not allowed ?
        if (Registry::getUtils()->isSearchEngine()) {
            return;
        }

        // fetching item ID
        if (!$sProductId) {
            $sBasketItemId = Registry::getConfig()->getRequestParameter('bindex');

            if ($sBasketItemId) {
                $oBasket = $this->getSession()->getBasket();
                //take params
                $aBasketContents = $oBasket->getContents();
                $oItem = $aBasketContents[$sBasketItemId];

                $sProductId = isset($oItem) ? $oItem->getProductId() : null;
            } else {
                $sProductId = Registry::getConfig()->getRequestParameter('aid');
            }
        }

        // fetching other needed info
        $dAmount = isset($dAmount) ? $dAmount : Registry::getConfig()->getRequestParameter('am');
        $aSel = isset($aSel) ? $aSel : Registry::getConfig()->getRequestParameter('sel');
        $aPersParam = $aPersParam ? $aPersParam : Registry::getConfig()->getRequestParameter('persparam');

        // adding articles
        if ($aProducts = $this->_getItems($sProductId, $dAmount, $aSel, $aPersParam, $blOverride)) {
            if(false === $this->validateBasketItemAppending($aProducts)){
                return;
            }
        }

        return parent::changeBasket($sProductId, $dAmount, $aSel, $aPersParam, $blOverride);
    }

    /**
     * Validates the product to be added against the current basket.
     *
     * @param array $aProducts
     * @return bool
     * @throws StandardException
     * @throws \PaBlo\ArticleLimitPerOrder\Domain\Exception\ModelLoadingException
     */
    private function validateBasketItemAppending(array $aProducts): bool
    {
        if (empty($aProducts)) {
            throw new StandardException('Given products can not be empty');
        }

        $container = $this->getDIContainer();

        if (null === $container) {
            throw new StandardException('Could not create service container');
        }

        $basket = Registry::getSession()->getBasket();

        /**@var ArticleLimitation $validationService */
        $validationService = $container->get(ArticleLimitation::class);

        foreach ($aProducts as $key => $aProduct) {
            $productId = $aProduct['aid'] ?? $key;  // arrgs !!!
            $amount = (int)$aProduct['am'];

            if (!$validationService->validate($productId, $amount, $basket)) {
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
                return false;
            }
        }

        return true;
    }

    /**
     * Create custom getter because component getter is marked as internal
     *
     * @return ContainerInterface|null
     */
    private function getDIContainer(): ?ContainerInterface
    {
        $factory = ContainerFactory::getInstance();
        if (null !== $factory) {
            return $factory->getContainer();
        }
    }
}
