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
class TabGrid extends \Gm\Panel\Widget\TabGrid
{
    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $this->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'text'      => 'ID',
                'tooltip'   => '#Album identifier',
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
                        $this->creator->t('Published'),
                        HtmlGrid::tplChecked('published==1')
                    )
                ]),
                'dataIndex' => 'name',
                'filter'    => ['type' => 'string'],
                'width'     => 200
            ],
            [
                'text'      => '#Description',
                'dataIndex' => 'description',
                'filter'    => ['type' => 'string'],
                'width'     => 200
            ],
            [
                'text'      => '#Author',
                'dataIndex' => 'author',
                'filter'    => ['type' => 'string'],
                'width'     => 120
            ],
            [
                'text'      => '#Type',
                'tooltip'   => '#Album type',
                'dataIndex' => 'pname',
                'width'     => 150
            ],
            [
                'xtype'   => 'g-gridcolumn-control',
                'width'   => 50,
                'tooltip' => '#Album items',
                'items'   => [
                    [
                        'iconCls'   => 'g-icon-svg g-icon_size_20 gm-media-gallery__icon-items',
                        'dataIndex' => 'itemsRoute',
                        'tooltip'   => '#Go to adding / editing album items',
                        'handler'   => 'loadWidgetFromCell'
                    ],
                ]
            ],
            [
                'text'        => ExtGrid::columnIcon('g-icon-m_visible', 'svg'),
                'xtype'       => 'g-gridcolumn-switch',
                'tooltip'     => '#The album has been published',
                'selector'    => 'grid',
                'filter'      => ['type' => 'boolean'],
                'collectData' => ['name', 'pname'],
                'dataIndex'   => 'published'
            ]
        ];

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $this->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        // инструмент "Добавить"
                        'add' => $this->addButton(),
                        // инструмент "Удалить"
                        'delete' => [
                            'iconCls' => 'g-icon-svg gm-media-gallery__icon-delete',
                            'tooltip' => '#Deleting selected albums'
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
                          'route'   => Gm::alias('@match', '/form/view/{id}?pid={pid}'),
                          'pattern' => 'grid.popupMenu.activeRecord'
                      ],
                      'handler' => 'loadWidget'
                ],
                '-',
                [
                    'text'        => '#Album items',
                    'iconCls'     => 'g-icon-svg gm-media-gallery__icon-items',
                    'handlerArgs' => [
                          'route'   => '{itemsRoute}',
                          'pattern' => 'grid.popupMenu.activeRecord'
                      ],
                      'handler' => 'loadWidget'
                ]
            ]
        ];

        // 2-й клик на строке сетки
        $this->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/form/view/{id}?pid={pid}')
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
                        ['route' => Gm::alias('@match', '/form/view/{id}?pid={pid}'), 'long' => true],
                        ['title' => $this->creator->t('Edit menu')]
                    ),
                    HtmlNav::widgetButton(
                        $this->creator->t('Album items'),
                        ['route' => '{itemsRoute}?pid={pid}', 'long' => true],
                        ['title' => $this->creator->t('Edit menu')]
                    )
                ]
            ]
        ]);

        $this
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
    }

    /**
     * Возвращает конфигурацию кнопки "Добавить" (Gm.view.grid.button.Split GmJS).
     * 
     * @return array<string, mixed>
     */
    protected function addButton(): array
    {
        $items = [];

        /** @var array $plugins Все плагины текущего модуля */
        $plugins = $this->creator->storageGet('plugins', []);
        foreach ($plugins as $plugin) {
            $items[] = [
                'text'        => $this->creator->t('Add "{0}"', [$plugin['name']]),
                'icon'        => $plugin['smallIcon'],
                'handler'     => 'loadWidget',
                'handlerArgs' => [
                    'route' => Gm::alias('@route', '/form/view?pid=' . $plugin['rowId'])
                ]
            ];
        }
        return [
            'xtype'       => 'g-gridbutton-split',
            'text'        => '#Add',
            'tooltip'     => '#Adding an album',
            'iconCls'     => 'g-icon-svg gm-media-gallery__icon-add',
            'handlerArgs' => $items[0]['handlerArgs'] ?? [],
            'menu'        => ['items' => $items]
        ];
    }
}
