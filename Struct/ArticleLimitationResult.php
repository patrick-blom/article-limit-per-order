<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Struct;

final class ArticleLimitationResult implements LimitationResult
{
    /**
     * @var string
     */
    private $articleId;

    /**
     * @var int
     */
    private $maxAmount;

    /**
     * @var bool
     */
    private $limitationActive;

    /**
     * @param string $articleId
     * @param int $maxAmount
     * @param bool $limitationActive
     */
    public function __construct(string $articleId, int $maxAmount, bool $limitationActive)
    {
        $this->articleId        = $articleId;
        $this->maxAmount        = $maxAmount;
        $this->limitationActive = $limitationActive;
    }

    /**
     * @return string
     */
    public function getArticleId(): string
    {
        return $this->articleId;
    }

    /**
     * @return int
     */
    public function getMaxAmount(): int
    {
        return $this->maxAmount;
    }

    /**
     * @return bool
     */
    public function getLimitationActive(): bool
    {
        return $this->limitationActive;
    }


}
