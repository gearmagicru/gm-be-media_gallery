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
use Gm\Db\Sql;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Data\Model\GridModel;
use Gm\Filesystem\Filesystem as Fs;

/**
 * Модель данных списка элементов альбома.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Model
 * @since 1.0
 */
class ItemsGrid extends GridModel
{
    /**
     * @var BaseModule|\Gm\Backend\MediaGallery\Module
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => true,
            'tableName'  => '{{gallery_items}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['gallery_id', 'alias' => 'gid'],
                ['index'], // порядковый номер
                ['name'], // название
                ['description'], // описание
                ['author'], // автор
                ['visible'], // отображется
                // медиа файл
                [ // внешний идентификатор
                    'item_id', 'alias' => 'itemId'
                ],
                [ // имя загруженного файла
                    'item_filename', 'alias' => 'itemFilename'
                ],
                [ // размер файла
                    'item_filesize', 'alias' => 'itemFilesize'
                ],
                [ // разрешение (только изображение)
                    'item_size', 'alias' => 'itemSize'
                ],
                [ // внешний URL-адрес
                    'item_src', 'alias' => 'itemSrc'
                ],
                [ // формат файла: JPG, MP3...
                    'item_format', 'alias' => 'itemFormat'
                ],
                [ // длительность видео, аудио
                    'item_length', 'alias' => 'itemLength'
                ],
                [ // mime-тип файла
                    'item_mime', 'alias' => 'itemMime'
                ],
                [ // локальный URL-адрес
                    'item_url', 'alias' => 'itemUrl'
                ],
                [ // подробнее о медиа файле
                    'item_details', 'alias' => 'itemDetails'
                ],
                // эскиз, обложка медиа файла
                [ // имя файла
                    'image_filename', 'alias' => 'imgFilename'
                ],
                [ // размер файла
                    'image_filesize', 'alias' => 'imgFilesize'
                ],
                [ // разрешение (только изображение)
                    'image_size', 'alias' => 'imgSize'
                ],
                [ // внешний URL-адрес
                    'image_src', 'alias' => 'imgSrc'
                ],
                [ // формат файла: JPG, MP3...
                    'image_format', 'alias' => 'imgFormat'
                ],
                [ // mime-тип файла
                    'image_mime', 'alias' => 'imgMime'
                ],
                [ // локальный URL-адрес
                    'image_url', 'alias' => 'imgUrl'
                ]
            ],
            'order' => ['name' => 'ASC'],
            'resetIncrements' => ['{{gallery_items}}']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_DELETE, function ($someRecords, &$canDelete) {
                $canDelete = $this->deleteFiles($this->getIdentifier(), $someRecords);
            })
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // если записи удалены
                if ($result > 0) {
                    $galleryId = $this->getGalleryId();
                    if ($galleryId) {
                        /** @var \Gm\Panel\Controller\GridController $controller */
                        $controller = $this->controller();
                        // обновить список
                        $controller->cmdReloadGrid('items-grid' . $galleryId);
                    }
                }
            });
    }

    /**
     * Удаляет файлы из папки альбома.
     * 
     * @param array<int, int> $rowsId Идентификаторы альбомов.
     * @param bool $someRecords Значение `true`, если удаление нескольких альбомов.
     * 
     * @return bool
     */
    protected function deleteFiles(array $rowsId, bool $someRecords): bool
    {
        // если удаление выбранных альбомов, а идент. нет
        if ($someRecords && empty($rowsId)) return true;

        /** @var string $publishedPath */
        $publishedPath = Gm::alias('@published');

        /** @var array $rows Все элементы альбомов */
        $rows = (new Item())->fetchAll(null, ['*'], $someRecords ? ['id' => $rowsId] : ['gallery_id' => $this->getGalleryId()]);
        $index = 1;
        foreach ($rows as $row) {
            // медиа файла
            if ($row['item_filename']) {
                $filename = $publishedPath . $row['item_url'];
                if (Fs::exists($filename)) {
                    if (!Fs::deleteFile($filename)) {
                        $this->controller()->errorResponse(
                            $this->module->t(
                                'Error deleting file "{0}" (no access)', [$filename]
                            ) 
                            . '<br>' .
                            $this->module->t(
                                'Album images ({0} of {1}) have been partially removed, but album entries remain', [$index, sizeof($rows)]
                            )
                        );
                        return false;
                    }
                }
            }
            // эскиз, обложка медиа файла
            if ($row['image_filename']) {
                $filename = $publishedPath . $row['image_url'];
                if (Fs::exists($filename)) {
                    if (!Fs::deleteFile($filename)) {
                        $this->controller()->errorResponse(
                            $this->module->t(
                                'Error deleting file "{0}" (no access)', [$filename]
                            ) 
                            . '<br>' .
                            $this->module->t(
                                'Album images ({0} of {1}) have been partially removed, but album entries remain', [$index, sizeof($rows)]
                            )
                        );
                        return false;
                    }
                }
            }
            $index++;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilter(Sql\AbstractSql $operator): void
    {
        $operator->where(['gallery_id' => $this->getGalleryId()]);

        parent::buildFilter($operator);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['name'] ?: $row['itemFilename'];
    }

    /**
     * {@inheritdoc}
     */
    protected function deleteAllProcessCondition(array &$where): void
    {
        $where['gallery_id'] = $this->getGalleryId();

        parent::deleteAllProcessCondition($where);
    }

    /**
     * {@inheritdoc}
     * 
     * Для правильного подсчета элементов в альбоме. {@see \Gm\Panel\Data\Model::deleteMessage()}
     */
    public function selectCount(string $tableName = null): int
    {
        /** @var Adapter $db  */
        $db = $this->getDb();
        $row = $db
            ->createCommand(
                $db->select()
                    ->from($tableName ?: $this->dataManager->tableName)
                    ->columns(['total' => new \Gm\Db\Sql\Expression('COUNT(*)')])
                    ->where(['gallery_id' => $this->getGalleryId()])
            )->queryOne();
        return isset($row['total']) ? (int) $row['total'] : 0;
    }

    /**
     * Возвращает идентификатор альбома.
     * 
     * @return int
     */
    protected function getGalleryId(): int
    {
        return Gm::$app->request->getQuery('gid', 0, 'int');
    }
}
