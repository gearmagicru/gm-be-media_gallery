<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\MediaGallery\Widget;

use Gm;

/**
 * Виджет для формирования интерфейса окна редактирования альбома.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Widget
 * @since 1.0
 */
class EditWindow extends \Gm\Panel\Widget\EditWindow
{
    /**
     * Порядковый номер вкладки.
     * 
     * @var int
     */
    protected int $activeTab = 0;

    /**
     * Тип альбома медиагалереи.
     * 
     * @var array
     */
    protected array $galleryType = [];

    /**
     * {@inheritdoc}
     */
    public array $passParams = ['activeTab', 'galleryType'];

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        $storage = $this->creator->getStorage();
        $storage->galleryTemp = Gm::getAlias('@runtime/gallery-' . md5(time()));

        if ($this->isInsertMode()) {
            $this->title = $this->creator->t('{form.title}', [$this->galleryType['name']]);

            /** @var \Gm\Panel\User\UserIdentity $identity */
            $identity = Gm::userIdentity();
            $author = $identity->getProfile()->getCallName();
        } else
            $author = '';

        // панель формы (Gm.view.form.Panel GmJS)
        $this->form->autoScroll = true;
        $this->form->bodyPadding = 10;
        $this->form->defaults = [
            'labelAlign' => 'right',
            'labelWidth' => 120
        ];
        $this->form->controller = 'gm-be-media_gallery-form';
        $this->form->continueTitle = $this->creator->t('Add and continue');
        $this->form->continueMsg = $this->creator->t('To add items to a gallery on this tab, you must first add a gallery. Add and continue?');
        $this->form->bodyPadding = 0;
        $this->form->loadJSONFile('/' . $this->galleryType['type'] . '-form', 'items', [
            '@uploadUrl' => '/' . Gm::getAlias('@match/upload/view/' . $this->getRowID()),
            '@activeTab' => $this->activeTab,
            '@author'    => $author,
            '@typeId'    => $this->galleryType['id']
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $this->width = 700;
        $this->height = 700;
        $this->layout = 'fit';
        $this->resizable = false;
        $this->responsiveConfig = [
            'height < 700' => ['height' => '99%'],
            'width < 700' => ['height' => '99%'],
        ];

        $this
            ->setNamespaceJS('Gm.be.media_gallery')
            ->addRequire('Gm.be.media_gallery.FormController')
            ->addRequire('Gm.view.IFrame');
    }
}
