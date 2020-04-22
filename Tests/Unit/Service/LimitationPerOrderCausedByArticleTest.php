<?php

namespace PaBlo\ArticleLimitPerOrder\Tests\Unit\Service;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Application\Model\Article;
use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Service\LimitationPerOrderCausedByArticle;
use PaBlo\ArticleLimitPerOrder\Struct\ArticleLimitationResult;
use PaBlo\ArticleLimitPerOrder\Struct\UnknownLimitationResult;

class LimitationPerOrderCausedByArticleTest extends UnitTestCase
{
    public function test_service_will_return_unknown_limitation_result_if_article_is_not_found(): void
    {
        $limitation = new LimitationPerOrderCausedByArticle();
        $result     = $limitation->getArticleLimitation('fakeId');

        $this->assertInstanceOf(UnknownLimitationResult::class, $result);
        $this->assertFalse($result->getLimitationActive());
        $this->assertSame('unknown', $result->getArticleId());
        $this->assertSame(-1, $result->getMaxAmount());
    }

    public function test_service_will_return_article_limitation_result_if_article_is_found(): void
    {
        $articleMock = $this->getMockBuilder(Article::class)
                            ->setMethods(['getProductId', 'load', 'getFieldData'])
                            ->getMock();

        $articleMock->expects($this->once())
                    ->method('load')
                    ->with(['fakeId'])
                    ->willReturn(true);

        $articleMock->expects($this->once())
                    ->method('getProductId')
                    ->willReturn('fakeId');

        $articleMock->expects($this->once())
                    ->method('getFieldData')
                    ->with(['PBMAXORDERLIMIT'])
                    ->willReturn(10);

        Registry::set(Article::class, $articleMock);

        $limitation = new LimitationPerOrderCausedByArticle();
        $result     = $limitation->getArticleLimitation('fakeId');

        $this->assertInstanceOf(ArticleLimitationResult::class, $result);
        $this->assertTrue($result->getLimitationActive());
        $this->assertSame('fakeId', $result->getArticleId());
        $this->assertSame(10, $result->getMaxAmount());
    }
}
