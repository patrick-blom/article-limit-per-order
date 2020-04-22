<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Struct;

final class UnknownLimitationResult implements LimitationResult
{

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getArticleId(): string
    {
        return 'unknown';
    }

    /**
     * @return int
     */
    public function getMaxAmount(): int
    {
        return -1;
    }

    /**
     * @return bool
     */
    public function getLimitationActive(): bool
    {
        return false;
    }


}
