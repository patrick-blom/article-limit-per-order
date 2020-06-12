<?php
declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Domain\Service;

use PaBlo\ArticleLimitPerOrder\Domain\Exception\ModelLoadingException;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\LimitationResult;

interface LimitationPerOrder
{
    /**
     * @param string $productId
     *
     * @return LimitationResult
     * @throws ModelLoadingException
     */
    public function checkForLimit(string $productId): LimitationResult;
}
