<?php declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Service\Contract;

use OxidEsales\Eshop\Application\Model\Contract\ArticleInterface;
use PaBlo\ArticleLimitPerOrder\Struct\ArticleLimitationResult;

/**
 * Interface LimitationPerOrder
 * @package PaBlo\ArticleLimitPerOrder\Service\Contract
 */
interface LimitationPerOrder
{
    /**
     * @param ArticleInterface $article
     *
     * @return ArticleLimitationResult
     */
    public function getArticleLimitation(ArticleInterface $article): ArticleLimitationResult;
}
