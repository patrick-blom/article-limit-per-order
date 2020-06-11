<?php
declare(strict_types=1);

namespace PaBlo\ArticleLimitPerOrder\Service;

use PaBlo\ArticleLimitPerOrder\Exception\ModelLoadingException;
use PaBlo\ArticleLimitPerOrder\Struct\LimitationResult;

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
