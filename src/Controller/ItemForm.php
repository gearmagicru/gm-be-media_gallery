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
use Gm\Stdlib\BaseObject;
use Gm\Mvc\Plugin\Plugin;
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы элемента компонента медиагалереи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Controller
 * @since 1.0
 */
class ItemForm extends FormController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'ItemForm';

    /**
     * Идентификатор компонента медиагалереи.
     * 
     * @see ItemForm::init()
     * 
     * @var int
     */
    protected int $galleryId = 0;

    /**
     * Плагин.
     * 
     * @var Plugin|null
     */
    protected ?Plugin $plugin = null;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, &$result) {
                switch ($action) {
                    case 'add':
                    case 'update':
                    case 'delete':
                    case 'view': 
                        // Идентификатор альбома
                        $this->galleryId = Gm::$app->request->getQuery('gid', 0, 'int');
                        if (empty($this->galleryId)) {
                            $result = $this->errorResponse(
                                GM_MODE_DEV ?
                                    Gm::t('app', 'Parameter passed incorrectly "{0}"', ['gid']) :
                                    $this->module->t('Unable to get component item (parameter error)')
                            );
                            return;
                        }
                        //break;

                    case 'data':
                        /** @var int $pluginId Идентификатор плагина */
                        $pluginId = Gm::$app->request->getQuery('pid', 0, 'int');
                        if ($pluginId) {
                            /** @var BasePlugin|null $plugin */
                            $plugin = Gm::$app->plugins->get($pluginId, ['module' => $this->module]);
                            if ($plugin === null) {
                                $result = $this->errorResponse(
                                    GM_MODE_DEV ?
                                        Gm::t('app', 'Parameter passed incorrectly "{0}"', ['pid']) :
                                        $this->module->t('Unable to get component item (parameter error)')
                                );
                                return;
                            }
                            $this->plugin = $plugin;
                        } else {
                            $result = $this->errorResponse(
                                GM_MODE_DEV ?
                                    Gm::t('app', 'Parameter "{0}" not specified', ['pid']) : 
                                    $this->module->t('Unable to get component item (parameter error)')
                            );
                            return;
                        }
                        break;
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow|false
    {
        return $this->plugin->getWidget('ItemEditWindow', [
            'galleryId' => $this->galleryId
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(string $name = null, array $config = []): ?BaseObject
    {
        $config['galleryId'] = $this->galleryId;
        if ($this->plugin)
            return $this->lastDataModel = $this->plugin->getModel($name, $config);
        else
            return $this->lastDataModel = $this->module->getModel($name, $config);
    }
}
