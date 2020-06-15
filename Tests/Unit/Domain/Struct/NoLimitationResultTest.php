<?php


namespace PaBlo\ArticleLimitPerOrder\Tests\Unit\Domain\Struct;


use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\LimitationResult;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult;

/**
 * Class NoLimitationResultTest
 * UNIT/INTEGRATION tests for struct class NoLimitationResult.
 *
 * @package PaBlo\ArticleLimitPerOrder\Tests\Unit\Domain\Struct
 */
class NoLimitationResultTest extends UnitTestCase
{
    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult::__construct
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult::create
     */
    public function test_factory_method_creates_object(): void
    {
        $result = NoLimitationResult::create();
        $this->assertInstanceOf(LimitationResult::class, $result);
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult::limit
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult::create
     */
    public function test_limit_will_return_the_number_for_no_limits(): void
    {
        $result = NoLimitationResult::create();
        $this->assertSame(0, $result->limit());
    }
}
