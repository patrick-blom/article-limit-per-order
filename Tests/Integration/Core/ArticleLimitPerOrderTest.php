<?php

namespace PaBlo\ArticleLimitPerOrder\Test\Integration\Core;

use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\TestingLibrary\UnitTestCase;
use PaBlo\ArticleLimitPerOrder\Core\ArticleLimitPerOrder;


/**
 * Class ArticleLimitPerOrderTest
 * UNIT/INTEGRATION tests for core class ArticleLimitPerOrder.
 *
 * @package PaBlo\ArticleLimitPerOrder\Test\Integration\Core
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
     * @covers \PaBlo\ArticleLimitPerOrder\Core\ArticleLimitPerOrder::onActivate
     */
    public function test_on_activate_will_add_custom_field_to_the_database(): void
    {
        // ensure field does not exists
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        $dbMetaDataHandler->executeSql(['ALTER TABLE oxarticles DROP PBMAXORDERLIMIT;']);
        $this->assertFalse($dbMetaDataHandler->fieldExists('PBMAXORDERLIMIT', 'oxarticles'));

        $this->SUT::onActivate();
        $this->assertTrue($dbMetaDataHandler->fieldExists('PBMAXORDERLIMIT', 'oxarticles'));
    }

    /**
     * @covers \PaBlo\ArticleLimitPerOrder\Core\ArticleLimitPerOrder::onDeactivate
     */
    public function test_on_deactivate_will_remove_template_blocks_from_database(): void
    {
        $container = ContainerFactory::getInstance()->getContainer();

        // ensure the module is active
        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
        $queryBuilderFactory = $container->get(QueryBuilderFactoryInterface::class);

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->select('oxid')
                     ->from('oxtplblocks', 'tpl')
                     ->where('tpl.oxmodule = :moduleId')
                     ->setParameters([
                         'moduleId' => 'articlelimitperorder'
                     ]);

        $result = $queryBuilder->execute()->fetchAll();
        $this->assertCount(1, $result);

        $this->SUT::onDeactivate();

        $result = $queryBuilder->execute()->fetchAll();
        $this->assertCount(0, $result);
    }
}

