<?php

namespace PaBlo\ArticleLimitPerOrder\Core;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class ArticleLimitPerOrder
 * @package PaBlo\ArticleLimitPerOrder\Core
 */
class ArticleLimitPerOrder
{
    /**
     * Adds a custom field to oxshops table to store the additional mail addresses
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public static function onActivate(): void
    {
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);

        if ( ! $dbMetaDataHandler->fieldExists('PBMAXORDERLIMIT', 'oxarticles')) {
            DatabaseProvider::getDb()->execute(
                "ALTER TABLE oxarticles ADD PBMAXORDERLIMIT double NOT NULL default 0 COMMENT 'Max quantity for one order';"
            );
        }
    }

    /**
     * Ensures that the template blocks will be cleared on module deactivation.
     */
    public static function onDeactivate(): void
    {
        $container = ContainerFactory::getInstance()->getContainer();
        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
        $queryBuilderFactory = $container->get(QueryBuilderFactoryInterface::class);
        $queryBuilder        = $queryBuilderFactory->create();

        $queryBuilder->select('oxid')
                     ->from('oxtplblocks')
                     ->where('oxmodule = :moduleId')
                     ->setParameters([
                         'moduleId' => 'articlelimitperorder'
                     ]);

        $row = $queryBuilder->execute()->fetch();

        // deletes are only allowed by primarykey
        if (null !== $row && count($row) > 0 && array_key_exists('oxid', $row)) {
            $queryBuilder->delete('oxtplblocks')
                         ->where('oxid = :id')
                         ->setParameters([
                             'id' => $row['oxid']
                         ]);
            $queryBuilder->execute();
        }
    }
}
