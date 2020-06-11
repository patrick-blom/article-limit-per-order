<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id' => 'articlelimitperorder',
    'title' => [
        'de' => 'PaBlo - Maximale Artikelbestellmenge pro Bestellung',
        'en' => 'PaBlo - maximum amount of single article per order',
    ],
    'description' => [
        'de' => 'Das Modul erweitert Artikeldaten um eine maximale Bestellmenge, diese kann pro Bestellung nicht überschirtten werden.',
        'en' => 'This module extends the article structure with the maximum order amount of a single article. This amount can not be exceeded per order',
    ],
    'version' => '1.0',
    'author' => 'Patrick Blom',
    'url' => 'https://www.patrick-blom.de/',
    'email' => 'info@patrick-blom.de',
    'extend' => [
        \OxidEsales\Eshop\Application\Component\BasketComponent::class => \PaBlo\ArticleLimitPerOrder\Application\Component\BasketComponent::class
    ],
    'blocks' => [
        [
            'template' => 'article_stock.tpl',
            'block' => 'admin_article_stock_form',
            'file' => 'Application/views/admin/blocks/article_stock__admin_article_stock_form.tpl'
        ]
    ],
    'events' => [
        'onActivate' => '\PaBlo\ArticleLimitPerOrder\Core\ArticleLimitPerOrder::onActivate',
        'onDeactivate' => '\PaBlo\ArticleLimitPerOrder\Core\ArticleLimitPerOrder::onDeactivate'
    ]
];
