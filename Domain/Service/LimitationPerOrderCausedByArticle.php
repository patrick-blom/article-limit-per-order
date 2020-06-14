<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Domain\Service;

use PaBlo\ArticleLimitPerOrder\Domain\Exception\CouldNotLoadArticle;
use PaBlo\ArticleLimitPerOrder\Domain\Model\Article;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\LimitationResult;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult;

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
                sprintf('article number: %s', $productId)
            );
        }

        $maximumOrderAmount = (int)$this->article->getFieldData('pbmaxorderlimit');

        if (0 === $maximumOrderAmount) {
            return NoLimitationResult::create();
        }

        return ArticleLimitationResult::fromAmount($maximumOrderAmount);
    }
}
