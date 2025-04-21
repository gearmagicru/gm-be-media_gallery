<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\MediaGallery\Controller;

use Gm;
use Gm\Panel\Http\Response;
use Gm\Panel\Controller\GridController;
use Gm\Backend\MediaGallery\Widget\TabGrid;

/**
 * Контроллер списка компонентов медиагалереи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Controller
 * @since 1.0
 */
class GalleryGrid extends GridController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'GalleryGrid';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, &$result) {
                switch ($action) {
                    case 'view': 
                        $this->module->storageSet(
                            'plugins', 
                            $this->module->getPluginsInfo(true, 'rowId', ['icon' => true])
                        );
                        break;
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        return new TabGrid();
    }

    /**
     * Действие "show" показывает фотоальбом.
     * 
     * @return Response
     */
    public function showAction(): Response
    {
        /** @var \Gm\Panel\Http\Response $response */
        $response = $this->getResponse();

        /** @var null|\Gm\Backend\MediaGallery\Model\GridRow $model */
        $model = $this->getModel($this->defaultModel . 'Row');
        if ($model === null) {
            return $this->errorResponse(
                Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel . 'Row'])
            );
        }

        /** @var null|\Gm\Backend\MediaGallery\Model\GridRow $form Запись по идентификатору в запросе */
        $form = $model->get();
        if ($form === null) {
            return $this->errorResponse(Gm::t(BACKEND, 'No data to perform action'));
        }

        // показать и сохранить альбом
        $form
            ->show()
            ->save();
        return $response;
    }

    /**
     * Действие "hide" скрывает альбом.
     * 
     * @return Response
     */
    public function hideAction(): Response
    {
        /** @var \Gm\Panel\Http\Response $response */
        $response = $this->getResponse();

        /** @var null|\Gm\Backend\MediaGallery\Model\GridRow $model */
        $model = $this->getModel($this->defaultModel . 'Row');
        if ($model === null) {
            return $this->errorResponse(
                Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel . 'Row'])
            );
        }

        /** @var null|\Gm\Backend\MediaGallery\Model\GridRow $form Запись по идентификатору в запросе */
        $form = $model->get();
        if ($form === null) {
            return $this->errorResponse(Gm::t(BACKEND, 'No data to perform action'));
        }

        // скрыть и сохранить фотоальбом
        $form
            ->hide()
            ->save();
        return $response;
    }
}
