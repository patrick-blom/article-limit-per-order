<?php

namespace PaBlo\ArticleLimitPerOrder\Core;

use Doctrine\DBAL\Driver\ResultStatement;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

/**
 * Class ArticleLimitPerOrder
 * @package PaBlo\ArticleLimitPerOrder\Core
 */
class ArticleLimitPerOrder
{
    /**
     * Adds a custom field to oxshops table to store the additional mail addresses
     *
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public static function onActivate(): void
    {
        // @phpstan-ignore-next-line
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);

        if (!$dbMetaDataHandler->fieldExists('PBMAXORDERLIMIT', 'oxarticles')) {
            DatabaseProvider::getDb()->execute(
                "ALTER TABLE oxarticles ADD PBMAXORDERLIMIT double NOT NULL default 0 COMMENT 'Maximum amount per single order';"
            );
        }
    }
}
