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
use Gm\Panel\Http\Response;
use Gm\Mvc\Controller\Controller;
use Gm\Backend\MediaGallery\Model\Gallery;

/**
 * Контроллер загрузки файлов альбома.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Controller
 * @since 1.0
 */
class Upload extends Controller
{
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
                /** @var int $pluginId */
                $pluginId = Gm::$app->request->getQuery('pid', 0, 'int');
                if ($pluginId) {
                    /** @var BasePlugin|null $plugin */
                    $plugin = Gm::$app->plugins->get($pluginId, ['module' => $this->module]);
                    if ($plugin === null)
                        $errorMessage = 
                            GM_MODE_DEV ?
                                Gm::t('app', 'Parameter passed incorrectly "{0}"', ['pid']) :
                                $this->module->t('Unable to perform action, one of the plugins is missing');
                    else
                        $this->plugin = $plugin;
                } else
                    $errorMessage =
                        GM_MODE_DEV ?
                            Gm::t('app', 'Parameter "{0}" not specified', ['pid']) : 
                            $this->module->t('Unable to perform action, one of the plugins is missing');

                if (isset($errorMessage)) {
                    /** @var Response $result */
                    $result = $this->getResponse();
                    $result->setStatusCode(400);
                    // ответ для сайта
                    if ($action === 'view') {
                        $result->setContent($errorMessage);
                    // ответ для панели
                    } else {
                        $result
                            ->setFormat(Response::FORMAT_JSONG)
                            ->meta
                                ->error($errorMessage);
                    }
                }
            });
    }

    /**
     * Действие "view" выводит интерфейс рабочего пространства.
     * 
     * @return string
     */
    public function viewAction(): string
    {
        return $this->renderLayout('upload-form');
    }

    /**
     * Действие "perform" загружает файл в альбом.
     * 
     * @return string
     */
    public function performAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        $response->setFormat(Response::FORMAT_JSONG);

        /** @var int $galleryId Идентификатор галереи */
        $galleryId = (int) Gm::$app->router->get('id');
        if (empty($galleryId)) {
            $response
                ->meta->error(
                    GM_MODE_PRO ? 
                        $this->t('Unable to load files') :
                        Gm::t('app', 'Parameter "{0}" not specified', ['id'])  
                );
            return $response;
        }

        /** @var Gallery|null $gallery Галерея */
        $gallery = (new Gallery())->get($galleryId);
        if ($gallery === null) {
            $response
                ->meta->error(
                    GM_MODE_PRO ? 
                        $this->t('Unable to load files') :
                        Gm::t('app', 'Invalid parameter specified "{0}"', ['id'])
                );
            return $response;
        }

        /** @var string|null $tempPath */
        $tempPath = $this->module->storageGet('tempPath');
        if (empty($tempPath)) {
            $response
                ->meta->error(
                    GM_MODE_PRO ? 
                        $this->t('Unable to load files') :
                        Gm::t('app', 'Parameter "{0}" not specified', ['tempPath'])  
                );
            return $response;
        }

        /** @var Object $upload */
        $upload = $this->plugin->getModel('Upload', [
            'galleryId'      => $galleryId,
            'galleryOptions' => $gallery->optionsToArray(),
            'tempPath'       => $tempPath
        ]);

        if (!$upload->run()) {
            $response
                ->meta->error($upload->getError());
            return $response;
        }

        /** @var array|null $tempFiles */
        $tempFiles = $this->module->storageGet('tempFiles', []);
        $tempFiles[] = $upload->getFile()->getResult();
        $this->module->storageSet('tempFiles', $tempFiles);

        // всплывающие сообщение
        $response
            ->meta
                ->cmdPopupMsg(
                    $this->t('File "{0}" has been uploaded successfully', 
                    [$upload->getFile()->name]), 
                    $this->t('Uploading'), 
                    'accept'
                );
        return $response;
    }
}
