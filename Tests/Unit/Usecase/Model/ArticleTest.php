<?php


namespace PaBlo\ArticleLimitPerOrder\Tests\Unit\Usecase\Model;


use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Usecase\Model\Article;

/**
 * Class ArticleTest
 * UNIT/INTEGRATION tests for model class Article.
 *
 * @package PaBlo\ArticleLimitPerOrder\Tests\Unit\Usecase\Model
 */
class ArticleTest extends UnitTestCase
{

    /**
     * @var Article
     */
    protected $SUT;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $articleMock;

    /**
     * Set SUT state before test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->articleMock = $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\Article::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getFieldData'])
            ->getMock();

        $this->SUT = new Article($this->articleMock);
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Usecase\Model\Article::__construct
     */
    public function test_constructor_works_as_expected(): void
    {
        $model = new Article((new \OxidEsales\Eshop\Application\Model\Article()));

        $delegate = $this->getProtectedClassProperty($model, 'oxArticle');
        $this->assertInstanceOf(\OxidEsales\Eshop\Application\Model\Article::class, $delegate);
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Usecase\Model\Article::load
     */
    public function test_loading_the_model_will_return_a_boolean(): void
    {
        $this->articleMock->expects($this->at(0))
            ->method('load')
            ->with('foo')
            ->willReturn(true);

        $this->articleMock->expects($this->at(1))
            ->method('load')
            ->with('bar')
            ->willReturn(false);

        $this->assertTrue($this->SUT->load('foo'));
        $this->assertFalse($this->SUT->load('bar'));
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Usecase\Model\Article::getFieldData
     */
    public function test_model_returns_expected_field_data(): void
    {
        $this->articleMock->expects($this->at(0))
            ->method('getFieldData')
            ->with('foo')
            ->willReturn('bar');


        $this->articleMock->expects($this->at(1))
            ->method('getFieldData')
            ->with('wusel-dusel')
            ->willReturn(null);

        $this->assertSame('bar', $this->SUT->getFieldData('foo'));
        $this->assertNull($this->SUT->getFieldData('wusel-dusel'));
    }
}
