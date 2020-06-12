<?php

declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Domain\Struct;

interface LimitationResult
{
    /**
     * Will return the amount of the limitation
     *
     * @return int
     */
    public function limit(): int;

    /**
     * Will return the type of limitation, e.g. article caused limitation or unknown limitation etc.
     *
     * @return string
     */
    public function typeOf(): string;
}
