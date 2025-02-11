<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\MediaGallery\Model;

use Gm;
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля записи альбома.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Model
 * @since 1.0
 */
class GalleryGridRow extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{gallery}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['published'],
                ['name']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                // вид альбома
                $pluginName = Gm::$app->request->post('pname', $this->module->t('Album'));
                // Если значение `published` отличается от устанавливаемого значения, то `$result = 1`. 
                // В остальных случаях  `$result = 0`, т.к. значение уже установлено и это считается
                // ошибкой. Чтобы не было такой ошибки, переопределяем `$message`.
                $message = [
                    'success' => true,
                    'message' => $this->module->t('{0} "{1}" - ' . ($this->published > 0 ? 'published' : 'unpublished'), [$pluginName, $this->name]),
                    'title'   => $this->t($this->published > 0 ? 'Publication' : 'Hide'),
                    'type'    => 'accept'
                ];
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }

    /**
     * Показывает альбом.
     * 
     * @return $this
     */
    public function show()
    {
        $this->published = 1;
        return $this;
    }

    /**
     * Скрывает альбом.
     * 
     * @return $this
     */
    public function hide()
    {
        $this->published = 0;
        return $this;
    }
}
