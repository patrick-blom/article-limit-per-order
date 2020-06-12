<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Domain\Model;

/**
 * @package PaBlo\ArticleLimitPerOrder\Domain\Model
 */
interface Article
{
    /**
     * @param string $productId
     * @return bool
     */
    public function load(string $productId): bool;

    /**
     * @param string $fieldName
     * @return mixed
     */
    public function getFieldData(string $fieldName);
}
