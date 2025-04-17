<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Media gallery',
    '{description}' => 'Managing site media gallery components',
    '{permissions}' => [
        'any'       => ['Full access', 'Viewing and making changes to an component'],
        'view'      => ['View', 'View component'],
        'read'      => ['Reading', 'Reading component'],
        'add'       => ['Adding', 'Adding component'],
        'edit'      => ['Editing', 'Editing component'],
        'delete'    => ['Deleting', 'Deleting component'],
        'clear'     => ['Clear', 'Deleting all components']
    ],

    // Upload: сообщения / заголовки
    'Uploading' => 'Загрузка',
    // Upload: сообщения / текст
    'Unable to load files' => 'Unable to load component files.',
    'File "{0}" has been uploaded successfully' => 'Файл "{0}" успешно загружен.',

    // Grid: панель инструментов
    'Add' => 'Add', 
    'Adding an component' => 'Adding an component',
    'Add "{0}"' => 'Add "{0}"',
    'Deleting selected components' => 'Deleting selected components',
    'Delete all components' => 'Delete all components',
    // Grid: контекстное меню записи
    'Edit menu' => 'Edit menu',
    'Component items' => 'Component items',
    // Grid: столбцы
    'Name' => 'Name',
    'Description' => 'ОписаDescriptionние',
    'Component identifier' => 'Component identifier',
    'Go to adding / editing component items' => 'Go to adding / editing component items',
    'Type' => 'Type',
    'Component type' => 'Component type',
    'Published' => 'Published',
    'The component has been published' => 'The component has been published',
    'Author' => 'Author',
    'yes' => 'yes',
    'no' => 'no',
    // Grid: сообщения / заголовки
    'Deleting a component' => 'Deleting a component',
    'Publication' => 'Publication',
    'Hide' => 'Hide',
    // Grid: сообщения / текст
    '{0} "{1}" - unpublished' => '{0} "{1}" - unpublished.',
    '{0} "{1}" - published' => '{0} "{1}" - published.',
    'Unable to perform action, one of the plugins is missing' 
        => 'Unable to perform action, one of the plugins is missing.',
    'Error deleting folder "{0}" (no access), component "{1}"' 
        => 'Error deleting folder "{0}" (no access), component "{1}".',
    'Folders ({0} of {1}) containing component files were partially deleted, but component records remain' 
        => 'Folders ({0} of {1}) containing component files were partially deleted, but component records remain.',

    // ItemsGrid: сообщения / текст
    'Unable to show gallery items (parameter error)' => 'Unable to show gallery items (parameter error)',
    'Unable to get component item (parameter error)' => 'Unable to get component item (parameter error)',
    'Error deleting file "{0}" (no access)' 
        => 'Error deleting file "{0}" (no access).',
    'Component images ({0} of {1}) have been partially removed, but component entries remain' 
        => 'Component images ({0} of {1}) have been partially removed, but component entries remain.'
];
