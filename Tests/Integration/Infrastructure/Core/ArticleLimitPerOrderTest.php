<?php

namespace PaBlo\ArticleLimitPerOrder\Tests\Integration\Infrastructure\Core;

use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Infrastructure\Core\ArticleLimitPerOrder;


/**
 * Class ArticleLimitPerOrderTest
 * UNIT/INTEGRATION tests for core class ArticleLimitPerOrder.
 *
 * @package PaBlo\ArticleLimitPerOrder\Tests\Integration\Core
 */
class ArticleLimitPerOrderTest extends UnitTestCase
{
    /**
     * Subject under the test.
     *
     * @var ArticleLimitPerOrder
     */
    protected $SUT;

    /**
     * Set SUT state before test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new ArticleLimitPerOrder();
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Infrastructure\Core\ArticleLimitPerOrder::onActivate
     */
    public function test_onActivate_will_add_custom_field_to_the_database(): void
    {
        // ensure field does not exists
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        $dbMetaDataHandler->executeSql(['ALTER TABLE oxarticles DROP PBMAXORDERLIMIT;']);
        $this->assertFalse($dbMetaDataHandler->fieldExists('PBMAXORDERLIMIT', 'oxarticles'));

        $this->SUT::onActivate();
        $this->assertTrue($dbMetaDataHandler->fieldExists('PBMAXORDERLIMIT', 'oxarticles'));
    }
}
