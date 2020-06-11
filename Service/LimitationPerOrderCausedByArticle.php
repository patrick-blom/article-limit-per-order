<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Service;

use OxidEsales\Eshop\Application\Model\Article;
use PaBlo\ArticleLimitPerOrder\Exception\CouldNotLoadArticle;
use PaBlo\ArticleLimitPerOrder\Struct\ArticleLimitationResult;
use PaBlo\ArticleLimitPerOrder\Struct\LimitationResult;
use PaBlo\ArticleLimitPerOrder\Struct\NoLimitationResult;

class LimitationPerOrderCausedByArticle implements LimitationPerOrder
{
    /**
     * @var Article;
     */
    private $article;

    /**
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * {@inheritDoc}
     */
    public function checkForLimit(
        string $productId
    ): LimitationResult {
        if (!$this->article->load($productId)) {
            throw new CouldNotLoadArticle(
                'Error during load',
                [
                    'productId' => $productId
                ]
            );
        }

        $maximumOrderAmount = (int)$this->article->getFieldData('pbmaxorderlimit');

        if (0 === $maximumOrderAmount) {
            return NoLimitationResult::create();
        }

        return ArticleLimitationResult::fromAmount($maximumOrderAmount);
    }
}
