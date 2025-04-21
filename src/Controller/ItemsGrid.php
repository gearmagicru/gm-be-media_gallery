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
use Gm\Mvc\Plugin\Plugin;
use Gm\Panel\Widget\Widget;
use Gm\Panel\Controller\GridController;
use Gm\Backend\MediaGallery\Model\Gallery;

/**
 * Контроллер списка элеменов компонента медиагалереи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Controller
 * @since 1.0
 */
class ItemsGrid extends GridController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'ItemsGrid';

    /**
     * Плагин.
     * 
     * @var Plugin|null
     */
    protected ?Plugin $plugin = null;

    /**
     * Атрибуты альбома.
     * 
     * @see ItemsGrid::init()
     * 
     * @var array
     */
    protected array $gallery;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, &$result) {
                // интерфейс списка элементов
                if ($action === 'view') {
                    /** @var int $galleryId Идентификатор альбома */
                    $galleryId = Gm::$app->request->getQuery('gid');
                    if (empty($galleryId)) {
                        $this->getResponse()
                            ->meta->error(
                                GM_MODE_PRO ? 
                                    $this->t('Unable to show gallery items (parameter error)') :
                                    Gm::t('app', 'Parameter "{0}" not specified', ['gallery'])
                            );
                        $result = false;
                        return;
                    }

                    /** @var null|Gallery Фотоальбом */
                    $gallery = (new Gallery())->get($galleryId);
                    if ($gallery === null) {
                        $this->getResponse()
                            ->meta->error(
                                GM_MODE_PRO ? 
                                    $this->t('Unable to show gallery items (parameter error)') :
                                    Gm::t('app', 'Parameter passed incorrectly "{0}"', ['gallery'])
                            );
                        $result = false;
                        return;
                    }
                    $this->gallery = $gallery->getAttributes();


                    if ($gallery->pluginId) {
                        /** @var BasePlugin|null $plugin */
                        $plugin = Gm::$app->plugins->get($gallery->pluginId, ['module' => $this->module]);
                        if ($plugin === null) {
                            $result = $this->errorResponse(
                                GM_MODE_DEV ?
                                    Gm::t('app', 'Parameter passed incorrectly "{0}"', ['plugin_id']) :
                                    $this->module->t('Unable to perform action, one of the plugins is missing')
                            );
                            return;
                        }
                        $this->plugin = $plugin;
                    } else {
                        $result = $this->errorResponse(
                            GM_MODE_DEV ?
                                Gm::t('app', 'Parameter "{0}" not specified', ['plugin_id']) : 
                                $this->module->t('Unable to perform action, one of the plugins is missing')
                        );
                        return;
                    }
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): Widget
    {
        return $this->plugin->getWidget('TabItemsGrid', [
        'creator' => $this->plugin,
            'gallery' => $this->gallery
        ]);

        /*return new TabItemsGrid([
            'gallery' => $this->gallery
        ]);*/
    }
}
