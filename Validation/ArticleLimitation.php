<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Validation;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\BasketItem;
use PaBlo\ArticleLimitPerOrder\Exception\ModelLoadingException;
use PaBlo\ArticleLimitPerOrder\Service\LimitationPerOrder;
use PaBlo\ArticleLimitPerOrder\Struct\NoLimitationResult;

class ArticleLimitation
{

    /**
     * @var LimitationPerOrder
     */
    private $limitationPerOrder;

    /**
     * @param LimitationPerOrder $limitationPerOrder
     */
    public function __construct(LimitationPerOrder $limitationPerOrder)
    {
        $this->limitationPerOrder = $limitationPerOrder;
    }

    /**
     * @param string $productId
     * @param int $amount
     * @param Basket $basket
     *
     * @return bool
     * @throws ModelLoadingException
     */
    public function validate(string $productId, int $amount, $basket): bool
    {
        $limitationResult = $this->limitationPerOrder->checkForLimit($productId);

        if ($limitationResult instanceof NoLimitationResult) {
            return true;
        }

        $desiredAmount = $amount;

        /**
         * @var  string $basketKey
         * @var  BasketItem $basketItem
         */
        foreach ($basket->getContents() as $basketKey => $basketItem) {
            if ($basketItem->getProductId() === $productId) {
                $desiredAmount += $basketItem->getAmount();
            }
        }

        return !($desiredAmount > $limitationResult->limit());
    }
}
