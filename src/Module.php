<?php
/**
 * Модуль веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\MediaGallery;

/**
 * Модуль медиагалереи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery
 * @since 1.0
 */
class Module extends \Gm\Panel\Module\Module
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.be.media_gallery';

    /**
     * {@inheritdoc}
     */
    public function controllerMap(): array
    {
        return [
            'form'  => 'GalleryForm',
            'grid'  => 'GalleryGrid',
            'items' => 'ItemsGrid',
            'item'  => 'ItemForm',
        ];
    }
}
