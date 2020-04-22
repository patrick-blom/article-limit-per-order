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
        'de' => 'PaBlo - Artikellimitierung pro Bestellung',
        'en' => 'PaBlo - article limitaion per order',
    ],
    'description' => [
        'de' => 'Dieses Modul erm&ouml;glicht die Begrenzung von Artikeln pro Bestellung (Haushalts&uuml;bliche Mengen)',
        'en' => 'This module makes it possible to limit the number of articles per order (budgetary quantities)',
    ],
    'version' => '1.0',
    'author' => 'Patrick Blom',
    'url' => 'https://www.patrick-blom.de/',
    'email' => 'info@patrick-blom.de',
    'extend' => [
    ],

    'blocks' => [
        [
            'template' => 'article_stock.tpl',
            'block' => 'admin_article_stock_form',
            'file' => 'views/admin/blocks/article_stock__admin_article_stock_form.tpl'
        ]
    ],
    'events' => [
        'onActivate' => '\PaBlo\ArticleLimitPerOrder\Core\ArticleLimitPerOrder::onActivate',
        'onDeactivate' => '\PaBlo\ArticleLimitPerOrder\Core\ArticleLimitPerOrder::onDeactivate'
    ]
];
