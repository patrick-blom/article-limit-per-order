<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Service;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Contract\ArticleInterface;
use PaBlo\ArticleLimitPerOrder\Struct\ArticleLimitationResult;
use PaBlo\ArticleLimitPerOrder\Struct\LimitationResult;
use PaBlo\ArticleLimitPerOrder\Struct\UnknownLimitationResult;

class LimitationPerOrderCausedByArticle
{
    /**
     * @var ArticleInterface
     */
    private $article;

    public function __construct()
    {
        $this->article = oxNew(Article::class);
    }

    /**
     * @param string $articleId
     *
     * @return LimitationResult
     */
    public function getArticleLimitation(string $articleId): LimitationResult
    {
        $loaded = $this->article->load($articleId);
        if (false === $loaded) {
            return new UnknownLimitationResult();
        }

        $articleLimitationActive = false;
        $maxAmount               = 0;

        $articleOrderLimit = $this->article->getFieldData('PBMAXORDERLIMIT');
        if ($articleOrderLimit > 0) {
            $maxAmount               = $articleOrderLimit;
            $articleLimitationActive = true;
        }

        return new ArticleLimitationResult(
            $this->article->getProductId(),
            $maxAmount,
            $articleLimitationActive
        );
    }
}
