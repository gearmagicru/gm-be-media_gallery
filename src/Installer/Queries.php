<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{gallery}}', '{{gallery_items}}'],
    'create' => [
        '{{gallery}}' => function () {
            return "CREATE TABLE IF NOT EXISTS {{gallery}} (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `plugin_id` int(11) unsigned DEFAULT NULL,
                `name` varchar(255) DEFAULT NULL,
                `description` text DEFAULT NULL,
                `cover` text DEFAULT NULL,
                `author` varchar(50) DEFAULT NULL,
                `published` tinyint(1) unsigned DEFAULT 1,
                `path` varchar(255) DEFAULT NULL,
                `options` text,
                `_updated_date` datetime DEFAULT NULL,
                `_updated_user` int(11) unsigned DEFAULT NULL,
                `_created_date` datetime DEFAULT NULL,
                `_created_user` int(11) unsigned DEFAULT NULL,
                `_lock` tinyint(1) unsigned DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        },
        
        '{{gallery_items}}' => function () {
            return "CREATE TABLE IF NOT EXISTS {{gallery_items}} (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `gallery_id` int(11) unsigned DEFAULT NULL,
                `index` int(11) unsigned DEFAULT '1',
                `name` varchar(255) DEFAULT NULL,
                `description` text,
                `author` varchar(50) DEFAULT NULL,
                `visible` tinyint(1) unsigned DEFAULT '1',
                `item_id` varchar(255) DEFAULT NULL,
                `item_filename` varchar(255) DEFAULT NULL,
                `item_filesize` decimal(10,2) unsigned DEFAULT 0.00,
                `item_size` varchar(100) DEFAULT NULL,
                `item_src` tinytext,
                `item_format` varchar(100) DEFAULT NULL,
                `item_length` varchar(100) DEFAULT NULL,
                `item_mime` varchar(100) DEFAULT NULL,
                `item_url` tinytext,
                `item_details` text,
                `image_filename` varchar(255) DEFAULT NULL,
                `image_filesize` decimal(10,2) unsigned DEFAULT 0.00,
                `image_size` varchar(100) DEFAULT NULL,
                `image_src` tinytext,
                `image_format` varchar(100) DEFAULT NULL,
                `image_mime` varchar(100) DEFAULT NULL,
                `image_url` tinytext,
                `_updated_date` datetime DEFAULT NULL,
                `_updated_user` int(11) unsigned DEFAULT NULL,
                `_created_date` datetime DEFAULT NULL,
                `_created_user` int(11) unsigned DEFAULT NULL,
                `_lock` tinyint(1) unsigned DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['create'],
        'uninstall' => ['drop']
    ]
];