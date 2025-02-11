<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'   => 'text-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND]
    ],

    'accessRules' => [
        // для авторизованных пользователей Панели управления
        [ // разрешение "Полный доступ" (any: read, add, edit, delete, clear)
            'allow',
            'permission'  => 'any',
            'controllers' => [
                'GalleryGrid' => ['data', 'view', 'update', 'delete', 'clear', 'show', 'hide'],
                'GalleryForm' => ['data', 'view', 'add', 'update', 'delete'],
                'ItemsGrid'   => ['data', 'view', 'update', 'delete', 'clear', 'moveup', 'movedown', 'move'],
                'ItemForm'    => ['data', 'view', 'add', 'update', 'delete'],
                'Upload'      => ['view', 'perform', 'index'],
                'Search'      => ['data', 'view'],
                'Trigger'     => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'GalleryGrid' => ['data', 'view', 'show', 'hide'],
                'GalleryForm' => ['data', 'view'],
                'ItemsGrid'   => ['data', 'view'],
                'ItemForm'    => ['data', 'view'],
                'Search'      => ['data', 'view'],
                'Trigger'     => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'GalleryGrid' => ['data'],
                'GalleryForm' => ['data'],
                'ItemsGrid'   => ['data'],
                'ItemForm'    => ['data'],
                'Search'      => ['data'],
                'Trigger'     => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Добавление" (add)
            'allow',
            'permission'  => 'add',
            'controllers' => [
                'GalleryForm' => ['add'],
                'ItemForm'    => ['add'],
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Изменение" (edit)
            'allow',
            'permission'  => 'edit',
            'controllers' => [
                'GalleryGrid' => ['update'],
                'GalleryForm' => ['update'],
                'ItemsGrid'   => ['update'],
                'ItemForm'    => ['update']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Удаление" (delete)
            'allow',
            'permission'  => 'delete',
            'controllers' => [
                'GalleryGrid' => ['delete'],
                'GalleryForm' => ['delete'],
                'ItemsGrid'   => ['delete'],
                'ItemForm'    => ['delete']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Очистка" (clear)
            'allow',
            'permission'  => 'clear',
            'controllers' => [
                'GalleryGrid' => ['clear'],
                'ItemsGrid'   => ['clear']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о модуле" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'viewManager' => [
        'id'          => 'gm-media-gallery-{name}',
        'useTheme'    => true,
        'useLocalize' => true,
        'viewMap'     => [
            // информации о модуле
            'info' => [
                'viewFile'      => '//backend/module-info.phtml', 
                'forceLocalize' => true
            ],
            'form' => '/form.json'
        ]
    ]
];
