<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Domain\Struct;

final class ArticleLimitationResult implements LimitationResult
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @param int $amount
     *
     * @return ArticleLimitationResult
     */
    public static function fromAmount(int $amount): self
    {
        return new self($amount);
    }

    /**
     * @param int $amount
     */
    private function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritDoc}
     */
    public function limit(): int
    {
        return $this->amount;
    }
}
