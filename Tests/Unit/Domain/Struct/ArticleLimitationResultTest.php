<?php


namespace PaBlo\ArticleLimitPerOrder\Tests\Unit\Domain\Struct;


use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\LimitationResult;

/**
 * Class ArticleLimitationResultTest
 * UNIT/INTEGRATION tests for struct class ArticleLimitationResult.
 *
 * @package PaBlo\ArticleLimitPerOrder\Tests\Unit\Domain\Struct
 */
class ArticleLimitationResultTest extends UnitTestCase
{
    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult::__construct
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult::fromAmount
     */
    public function test_factory_method_creates_object(): void
    {
        $result = ArticleLimitationResult::fromAmount(0);
        $this->assertInstanceOf(LimitationResult::class, $result);
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult::limit
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult::fromAmount
     */
    public function test_result_will_return_the_expected_limit(): void
    {
        $result = ArticleLimitationResult::fromAmount(0);
        $this->assertSame(0, $result->limit());

        $result = ArticleLimitationResult::fromAmount(10);
        $this->assertSame(10, $result->limit());
    }

}
