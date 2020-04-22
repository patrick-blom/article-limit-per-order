<?php

namespace PaBlo\ArticleLimitPerOrder\Struct;

interface LimitationResult
{
    /**
     * @return string
     */
    public function getArticleId(): string;

    /**
     * @return int
     */
    public function getMaxAmount(): int;

    /**
     * @return bool
     */
    public function getLimitationActive(): bool;
}
