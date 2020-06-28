<?php


namespace PaBlo\ArticleLimitPerOrder\Tests\Unit\Adapter\Validation;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\BasketItem;
use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Adapter\Validation\ArticleLimitation;
use PaBlo\ArticleLimitPerOrder\Domain\Service\LimitationPerOrderCausedByArticle;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult;

/**
 * Class ArticleLimitationTest
 * UNIT/INTEGRATION tests for validator class ArticleLimitation.
 *
 * @package PaBlo\ArticleLimitPerOrder\Tests\Unit\Adapter\Validation
 */
class ArticleLimitationTest extends UnitTestCase
{

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Adapter\Validation\ArticleLimitation::__construct
     */
    public function test_article_limitation_constructor_works_as_expected(): void
    {
        $limitationPerOrderMock = $this->getMockBuilder(LimitationPerOrderCausedByArticle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleLimitationValidator = new ArticleLimitation($limitationPerOrderMock);

        $property = $this->getProtectedClassProperty($articleLimitationValidator, 'limitationPerOrder');

        $this->assertInstanceOf(LimitationPerOrderCausedByArticle::class, $property);
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Adapter\Validation\ArticleLimitation::validate
     */
    public function test_validator_skips_products_with_no_limit(): void
    {
        $limitationPerOrderMock = $this->getMockBuilder(LimitationPerOrderCausedByArticle::class)
            ->disableOriginalConstructor()
            ->setMethods(['checkForLimit'])
            ->getMock();

        $limitationPerOrderMock->expects($this->once())
            ->method('checkForLimit')
            ->with('0000')
            ->willReturn(NoLimitationResult::create());

        $articleLimitationValidator = new ArticleLimitation($limitationPerOrderMock);

        $this->assertTrue($articleLimitationValidator->validate('0000', 1, (new Basket())));
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Adapter\Validation\ArticleLimitation::validate
     */
    public function test_validator_return_expected_values(): void
    {
        $limitationPerOrderMock = $this->getMockBuilder(LimitationPerOrderCausedByArticle::class)
            ->disableOriginalConstructor()
            ->setMethods(['checkForLimit'])
            ->getMock();

        $limitationPerOrderMock->expects($this->exactly(2))
            ->method('checkForLimit')
            ->with('12345')
            ->willReturn(ArticleLimitationResult::fromAmount(2));

        $basketItemMock = $this->getMockBuilder(BasketItem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAmount', 'getProductId'])
            ->getMock();

        $basketItemMock->expects($this->exactly(2))
            ->method('getAmount')
            ->willReturn(1);


        $basketItemMock->expects($this->exactly(2))
            ->method('getProductId')
            ->willReturn('12345');

        $basketMock = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContents'])
            ->getMock();

        $basketMock->expects($this->exactly(2))
            ->method('getContents')
            ->willReturn(['12345' => $basketItemMock]);

        $articleLimitationValidator = new ArticleLimitation($limitationPerOrderMock);

        $this->assertTrue($articleLimitationValidator->validate('12345',1, $basketMock));
        $this->assertFalse($articleLimitationValidator->validate('12345',2, $basketMock));
    }
}
