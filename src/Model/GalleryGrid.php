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
use Gm\Panel\Data\Model\GridModel;
use Gm\Filesystem\Filesystem as Fs;

/**
 * Модель данных списка компонентов медиагалереи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Model
 * @since 1.0
 */
class GalleryGrid extends GridModel
{
    /**
     * Маршрут к элементам компонентов медиагалереи.
     * 
     * @see Grid::beforeSelect()
     * 
     * @var string
     */
    protected string $itemsRoute = '';

    /**
     * Информация о плагинах модуля.
     * 
     * @var array<int, array<string, mixed>>
     */
    protected array $plugins = [];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => true,
            'tableName'  => '{{gallery}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                [ // идент. плагина
                    'plugin_id',
                    'alias' => 'pid'
                ],
                ['name'], // название
                ['description'], // описание
                ['cover'], // обложка
                ['author'], // автор
                ['published'], // опубликован
                [ // вид альбома
                    'pname', 
                    'direct' => 'plugin_id'
                ]
            ],
            'order'        => ['name' => 'DESC'],
            'dependencies' => [
                'deleteAll' => ['{{gallery_items}}'],
                'delete'    => ['{{gallery_items}}' => ['gallery_id' => 'id']]
            ],
            'resetIncrements' => ['{{gallery}}', '{{gallery_items}}']
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
                        ->cmdPopupMsg($message['message'], $this->module->t('Deleting a component'), $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * Удаляет файлы из папки компонента медиагалереи.
     * 
     * @param array<int, int> $rowsId Идентификаторы компонентов.
     * @param bool $someRecords Значение `true`, если удаление нескольких компонентов.
     * 
     * @return bool
     */
    protected function deleteFiles(array $rowsId, bool $someRecords): bool
    {
        // если удаление выбранных компонентов, а идент. нет
        if ($someRecords && empty($rowsId)) return true;

        /** @var string $publishedPath */
        $publishedPath = Gm::alias('@published');

        /** @var array $rows Все компоненты */
        $rows = (new Gallery())->fetchAll(null, ['*'], $someRecords ? ['id' => $rowsId] : null);
        $index = 1;
        foreach ($rows as $row) {
            // если локальный путь к альбому еще не указан
            if (empty($row['path'])) continue;

            /** @var string $realPath Полный путь к папке альбома */
            $realPath = $publishedPath . $row['path'];
            if (Fs::exists($realPath)) {
                if (!Fs::deleteDirectory($realPath)) {
                    $this->controller()->errorResponse(
                        $this->module->t(
                            'Error deleting folder "{0}" (no access), component "{1}"', [$row['path'], $row['name']]
                        ) 
                        . '<br>' .
                        $this->module->t(
                            'Folders ({0} of {1}) containing component files were partially deleted, but component records remain', [$index, sizeof($rows)]
                        )
                    );
                    return false;
                }
            }
            $index++;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSelect(mixed $command = null): void
    {
        $this->itemsRoute = Gm::alias('@match', '/items/view');
        // информация о плагинах модуля
        $this->plugins = $this->module->storageGet('plugins');
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // идент. плагина
        $pluginId = $row['pid'];
        // если указан плагин
        if ($pluginId) {
            $row['pname'] = $this->plugins[$pluginId]['name'] ?? SYMBOL_NONAME;
        }
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['name'];
        // маршрут к изображениям фотоальбомов
        $row['itemsRoute'] = $this->itemsRoute . '?gid=' . $row['id'];
    }
}
