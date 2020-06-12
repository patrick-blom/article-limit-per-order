<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Domain\Struct;

final class NoLimitationResult implements LimitationResult
{
    private const NOLIMIT = 'nolimit';

    /**
     * @return NoLimitationResult
     */
    public static function create(): self
    {
        return new self();
    }

    private function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function limit(): int
    {
        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function typeOf(): string
    {
        return self::NOLIMIT;
    }
}
