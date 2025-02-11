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
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;

/**
 * Виджет для формирования интерфейса вкладки с сеткой данных альбомов.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\MediaGallery\Widget
 * @since 1.0
 */
class TabItemsGrid extends \Gm\Panel\Widget\TabGrid
{
    
    /**
     * {@inheritdoc}
     */
    public array $passParams = ['gallery'];

    /**
     * Атрибуты фотоальбома.
     * 
     * @see ItemsGrid::init()
     * 
     * @var array
     */
    protected array $gallery;

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // вкладка
        $this->setViewID('gm-media-gallery-items-tab' . $this->gallery['id'], false);
        $this->title = $this->creator->t('Gallery "{0}"', [$this->gallery['name']]);
        $this->tooltip['title'] = $this->title;

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $this->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'text'      => 'ID',
                'tooltip'   => '#Image identifier',
                'dataIndex' => 'id',
                'filter'    => ['type' => 'numeric'],
                'width'     => 70
            ],
            [
                'text'    => ExtGrid::columnInfoIcon($this->creator->t('Name')),
                'cellTip' => HtmlGrid::tags([
                    HtmlGrid::header('{name:ellipsis(50)}'),
                    HtmlGrid::fieldLabel($this->creator->t('Description'), '{description}'),
                    HtmlGrid::fieldLabel(
                        $this->creator->t('Visible on site'),
                        HtmlGrid::tplChecked('visible==1')
                    )
                ]),
                'dataIndex' => 'name',
                'filter'    => ['type' => 'string'],
                'width'     => 220
            ],
            [
                'text'    => '#Description',
                'dataIndex' => 'description',
                'filter'    => ['type' => 'string'],
                'width'     => 200
            ],
            [
                'text'    => 'Изображение',
                'columns' => [
                    [
                        'text'      => 'Тип',
                        'tooltip'   => 'Тип изображения',
                        'dataIndex' => 'imgType',
                        'filter'    => ['type' => 'string'],
                        'width'     => 90
                    ],
                    [
                        'text'      => 'Нзвание файла',
                        'dataIndex' => 'imgFilename',
                        'filter'    => ['type' => 'string'],
                        'width'     => 200
                    ],
                    [
                        'text'      => 'Размер файла (Мб)',
                        'dataIndex' => 'imgFilesize',
                        'filter'    => ['type' => 'string'],
                        'width'     => 150
                    ],
                    [
                        'text'      => 'Размер (пкс)',
                        'tooltip'   => 'Размер изображения в пикселях',
                        'dataIndex' => 'imgSize',
                        'filter'    => ['type' => 'string'],
                        'width'     => 150
                    ]
                ]
            ],
            [
                'text'    => 'Эскиз',
                'columns' => [
                    [
                        'text'      => 'Тип',
                        'tooltip'   => 'Тип изображения эскиза',
                        'dataIndex' => 'thumbType',
                        'filter'    => ['type' => 'string'],
                        'width'     => 90
                    ],
                    [
                        'text'      => 'Нзвание файла',
                        'dataIndex' => 'thumbFilename',
                        'filter'    => ['type' => 'string'],
                        'width'     => 200
                    ],
                    [
                        'text'      => 'Размер файла (Мб)',
                        'dataIndex' => 'thumbFilesize',
                        'filter'    => ['type' => 'string'],
                        'width'     => 150
                    ],
                    [
                        'text'      => 'Размер (пкс)',
                        'tooltip'   => 'Размер изображения эскиза в пикселях',
                        'dataIndex' => 'thumbSize',
                        'filter'    => ['type' => 'string'],
                        'width'     => 150
                    ]
                ]
            ],
            [
                'text'        => ExtGrid::columnIcon('g-icon-m_visible', 'svg'),
                'xtype'       => 'g-gridcolumn-switch',
                'tooltip'     => '#Album visibility',
                'selector'    => 'grid',
                'collectData' => ['name'],
                'dataIndex'   => 'visible'
            ]
        ];

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $this->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        // инструмент "Добавить"
                        'add' => [
                            'iconCls'     => 'g-icon-svg gm-media-gallery__icon-item-add',
                            'handlerArgs' => ['route' => Gm::alias('@match', '/item?gallery=' . $this->gallery['id'])],
                            'tooltip'     => '#Adding a album',
                            'caching'     => false
                        ],
                        // инструмент "Добавить"
                        ExtGrid::button([
                            'text'        => Gm::t(BACKEND, 'Add'),
                            'tooltip'     => Gm::t(BACKEND, 'Adding a new record'),
                            'iconCls'     => 'g-icon-svg gm-media-galleries__icon-items-add',
                            'handlerArgs' => ['route' => Gm::alias('@match', '/item?gallery=' . $this->gallery['id'])],
                            'handler'     => 'loadWidget'
                        ]),
                        // инструмент "Удалить"
                        'delete' => [
                            'iconCls' => 'g-icon-svg gm-media-galleries__icon-item-delete',
                            'tooltip' => '#Deleting selected images'
                        ],
                        'cleanup' => [
                            'tooltip' => '#Delete all albums'
                        ],
                        '-',
                        'edit',
                        'select',
                        '-',
                        'refresh'
    
                    ]
                ],
                'columns',
                'search'
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $this->grid->popupMenu = [
            'cls'        => 'g-gridcolumn-popupmenu',
            'titleAlign' => 'center',
            'items'      => [
                [
                    'text'        => '#Edit menu',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handlerArgs' => [
                          'route'   => Gm::alias('@match', '/item/view/{id}?media=' . $this->gallery['id']),
                          'pattern' => 'grid.popupMenu.activeRecord'
                      ],
                      'handler' => 'loadWidget'
                ]
            ]
        ];

        $this->grid->setViewID('gm-media-gallery-items-grid' . $this->gallery['id'], false);
        // 2-й клик на строке сетки
        $this->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/item/view/{id}?gallery=' . $this->gallery['id'])
        ];
        // сортировка сетки по умолчанию
        $this->grid->sorters = [
           ['property' => 'name', 'direction' => 'ASC']
        ];
        // количество строк в сетке
        $this->grid->store->pageSize = 50;
        // поле аудита записи
        $this->grid->logField = 'header';
        // плагины сетки
        $this->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $this->grid->bodyCls = 'g-grid_background';
        $this->grid->router->setAll([
            'rules' => [
                'clear'      => '{route}/clear?gallery=' . $this->gallery['id'],
                'delete'     => '{route}/delete?gallery=' . $this->gallery['id'],
                'data'       => '{route}/data?gallery=' . $this->gallery['id'],
                'deleteRow'  => '{route}/delete/{id}?gallery=' . $this->gallery['id'],
                'updateRow'  => '{route}/update/{id}'
            ],
            'route' => Gm::alias('@backend', '/media-gallery/items')
        ]);

        // панель навигации (Gm.view.navigator.Info GmJS)
        $this->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('{name}'),
            ['div', '{description}', ['align' => 'center']],
            HtmlNav::fieldLabel(
                $this->creator->t('Visible on site'),
                HtmlNav::tplChecked('visible==1')
            ),
            ['fieldset',
                [
                    HtmlNav::widgetButton(
                        $this->creator->t('Edit menu'),
                        ['route' => Gm::alias('@match', '/form/view/{id}'), 'long' => true],
                        ['title' => $this->creator->t('Edit menu')]
                    ),
                    HtmlNav::widgetButton(
                        $this->creator->t('Album items'),
                        ['route' => '{itemsRoute}', 'long' => true],
                        ['title' => $this->creator->t('Edit menu')]
                    )
                ]
            ]
        ]);

        $this
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
    }
}
