<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\MediaGallery\Model;

use Gm\Db\ActiveRecord;
use Gm\Data\DataManager as DM;

/**
 * Активная запись галереи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Model
 * @since 1.0
 */
class Gallery extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function tableName(): string
    {
        return '{{gallery}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id'          => 'id',
            'pluginId'    => 'plugin_id',
            'name'        => 'name',
            'description' => 'description',
            'cover'       => 'cover',
            'author'      => 'author',
            'published'   => 'published',
            'path'        => 'path',
            'options'     => 'options',
            // атрибуты аудита записи
            'createdDate' => DM::AR_CREATED_DATE, // дата добавления
            'createdUser' => DM::AR_CREATED_USER, // добавлено пользователем
            'updatedDate' => DM::AR_UPDATED_DATE, // дата изменения
            'updatedUser' => DM::AR_UPDATED_USER, // изменено пользователем
            'lock'        => DM::AR_LOCK // заблокировано
        ];
    }

    /**
     * Возвращает запись по указанному значению первичного ключа.
     * 
     * @see ActiveRecord::selectByPk()
     * 
     * @param mixed $id Идентификатор записи.
     * 
     * @return null|Gallery Активная запись при успешном запросе, иначе `null`.
     */
    public function get(mixed $identifier): ?static
    {
        return $this->selectByPk($identifier);
    }

    /**
     * optionsToArray
     * 
     * @return array<string, mixed>
     */
    public function optionsToArray(): array
    {
        if ($this->options) {
            if (is_string($this->options)) {
                return json_decode($this->options, true);
            }
            return $this->options;
        }
        return [];
    }
}
