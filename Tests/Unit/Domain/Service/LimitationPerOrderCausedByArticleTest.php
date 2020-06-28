<?php


namespace PaBlo\ArticleLimitPerOrder\Tests\Unit\Domain\Service;

use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Adapter\Model\Article;
use PaBlo\ArticleLimitPerOrder\Domain\Exception\CouldNotLoadArticle;
use PaBlo\ArticleLimitPerOrder\Domain\Service\LimitationPerOrderCausedByArticle;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\ArticleLimitationResult;
use PaBlo\ArticleLimitPerOrder\Domain\Struct\NoLimitationResult;

/**
 * Class LimitationPerOrderCausedByArticleTest
 * UNIT/INTEGRATION tests for service class LimitationPerOrderCausedByArticle.
 *
 * @package PaBlo\ArticleLimitPerOrder\Tests\Unit\Domain\Service
 */
class LimitationPerOrderCausedByArticleTest extends UnitTestCase
{
    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Service\LimitationPerOrderCausedByArticle::__construct
     */
    public function test_limitation_per_article_service_constructor_works_as_expected(): void
    {
        $articleMock = $this->getMockBuilder(Article::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleLimitationValidator = new LimitationPerOrderCausedByArticle($articleMock);

        $property = $this->getProtectedClassProperty($articleLimitationValidator, 'article');

        $this->assertInstanceOf(Article::class, $property);
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Service\LimitationPerOrderCausedByArticle::checkForLimit
     */
    public function test_service_throws_exception_if_product_can_not_be_loaded(): void
    {
        $articleMock = $this->getMockBuilder(Article::class)
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMock();

        $articleMock->expects($this->once())
            ->method('load')
            ->willReturn(false);

        $articleLimitation = new LimitationPerOrderCausedByArticle($articleMock);

        $this->expectException(CouldNotLoadArticle::class);
        $this->expectExceptionMessage('article number: 12345');

        $articleLimitation->checkForLimit('12345');
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Service\LimitationPerOrderCausedByArticle::checkForLimit
     */
    public function test_service_retruns_no_limit_object_if_no_limit_is_set(): void
    {
        $articleMock = $this->getMockBuilder(Article::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getFieldData'])
            ->getMock();

        $articleMock->expects($this->once())
            ->method('load')
            ->with('12345')
            ->willReturn(true);

        $articleMock->expects($this->once())
            ->method('getFieldData')
            ->with('pbmaxorderlimit')
            ->willReturn(0);

        $articleLimitation = new LimitationPerOrderCausedByArticle($articleMock);

        $result = $articleLimitation->checkForLimit('12345');

        $this->assertInstanceOf(NoLimitationResult::class, $result);
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Domain\Service\LimitationPerOrderCausedByArticle::checkForLimit
     */
    public function test_service_retruns_limitation_object_if_limit_is_set(): void
    {
        $articleMock = $this->getMockBuilder(Article::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getFieldData'])
            ->getMock();

        $articleMock->expects($this->once())
            ->method('load')
            ->with('12345')
            ->willReturn(true);

        $articleMock->expects($this->once())
            ->method('getFieldData')
            ->with('pbmaxorderlimit')
            ->willReturn(2);

        $articleLimitation = new LimitationPerOrderCausedByArticle($articleMock);

        $result = $articleLimitation->checkForLimit('12345');

        $this->assertInstanceOf(ArticleLimitationResult::class, $result);
        $this->assertSame(2, $result->limit());
    }
}
