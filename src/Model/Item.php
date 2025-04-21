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
use Gm\Db\Sql\Select;
use Gm\Db\Sql\Expression;

/**
 * Активная запись элемента компонента медиагалереи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Model
 * @since 1.0
 */
class Item extends ActiveRecord
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
        return '{{gallery_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id'          => 'id',
            'galleryId'   => 'gallery_id', // идент. компонента медиагалереи
            'index'       => 'index', // порядковый номер
            'name'        => 'name', // название
            'description' => 'description', // описание
            'author'      => 'author', // автор
            'visible'     => 'visible', // отображется
            // медиа файл
            'itemId'       => 'item_id', // внешний идентификатор
            'itemFilename' => 'item_filename', // имя загруженного файла
            'itemFilesize' => 'item_filesize', // размер файла
            'itemSize'     => 'item_size', // разрешение (только изображение)
            'itemSrc'      => 'item_src', // внешний URL-адрес
            'itemFormat'   => 'item_format', // формат файла: JPG, MP3...
            'itemLength'   => 'item_length', // длительность видео, аудио
            'itemMime'     => 'item_mime', // mime-тип файла
            'itemUrl'      => 'item_url', // локальный URL-адрес
            'itemDetails'  => 'item_details', // подробнее о медиа файле
            // эскиз, обложка медиа файл
            'imgFilename' => 'image_filename', // имя файла
            'imgFilesize' => 'image_filesize', // размер файла
            'imgSize'     => 'image_size', // разрешение (только изображение)
            'imgSrc'      => 'image_src', // внешний URL-адрес
            'imgFormat'   => 'image_format', // формат файла: JPG, MP3...
            'imgMime'     => 'image_mime', // mime-тип файла
            'imgUrl'      => 'image_url', // локальный URL-адрес
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
     * @param mixed $id Идентификатор элемента компонента медиагалереи.
     * 
     * @return null|Item Активная запись при успешном запросе, иначе `null`.
     */
    public function get(mixed $identifier): ?static
    {
        return $this->selectByPk($identifier);
    }

    /**
     * Скрывает все элементы компонента медиагалереи.
     * 
     * @param int $galleryId Идентификатор компонента медиагалереи.
     * 
     * @return false|int Если значение `false`, ошибка выполнения инструкции SQL. Иначе количество 
     *     скрытых элементов компонента медиагалереи.
     */
    public function hideAll(int $galleryId): false|int
    {
        return $this->updateRecord(['visible' => 0], ['gallery_id' => $galleryId]);
    }

    /**
     * Скрывает элемент компонента медиагалереи.
     * 
     * @return void
     */
    public function hide(): void
    {
        $this->visible = 0;
        $this->save();
    }

    /**
     * Показывает все элементы компонента медиагалереи.
     * 
     * @param int $galleryId Идентификатор компонента медиагалереи.
     * 
     * @return false|int Если значение `false`, ошибка выполнения инструкции SQL. Иначе количество 
     *     видимых элементов компонента медиагалереи.
     */
    public function showAll(int $galleryId): false|int
    {
        return $this->updateRecord(['visible' => 1], ['gallery_id' => $galleryId]);
    }

    /**
     * Показывает элементы компонента медиагалереи.
     * 
     * @return void
     */
    public function show(): void
    {
        $this->visible = 1;
        $this->save();
    }

    /**
     * Возвращает порядковый номер последнего элемента медигалереи.
     * 
     * @param int $galleryId Идент. медегалереи.
     * 
     * @return int
     */
    public function getLastIndex(int $galleryId): int
    {
        /** @var Select $select */
        $select = new Select($this->tableName());
        $select->quantifier(new Expression('MAX(`index`)'));
        $select->where(['gallery_id' => $galleryId]);

        /** @var array $columns */
        $columns = $this->db
                ->createCommand($select)
                    ->queryColumn();
        return empty($columns[0]) ? 0 : $columns[0];
    }
}
