<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Usecase\Model;

use PaBlo\ArticleLimitPerOrder\Domain\Model\Article as DomainArticle;
use OxidEsales\Eshop\Application\Model\Article as oxArticle;

/**
 * @package PaBlo\ArticleLimitPerOrder\Usecase\Model
 */
class Article implements DomainArticle
{
    /**
     * @var oxArticle $oxArticle
     */
    private $oxArticle;

    /**
     * @param oxArticle $oxArticle
     */
    public function __construct(oxArticle $oxArticle)
    {
        $this->oxArticle = $oxArticle;
    }

    /**
     * @param string $productId
     * @return bool
     */
    public function load(string $productId): bool
    {
        return (bool)$this->oxArticle->load($productId);
    }

    /**
     * @param string $fieldName
     * @return mixed|void
     */
    public function getFieldData(string $fieldName)
    {
        return $this->oxArticle->getFieldData($fieldName);
    }
}
