<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Медигалерея',
    '{description}' => 'Управление компонентами медиагалереи сайта',
    '{permissions}' => [
        'any'       => ['Полный доступ', 'Просмотр и внесение изменений в компонента'],
        'view'      => ['Просмотр', 'Просмотр компонента'],
        'read'      => ['Чтение', 'Чтение компонента'],
        'add'       => ['Добавление', 'Добавление компонента'],
        'edit'      => ['Изменение', 'Изменение компонента'],
        'delete'    => ['Удаление', 'Удаление компонента'],
        'clear'     => ['Очистка', 'Удаление всех компонента']
    ],

    // Upload: сообщения / заголовки
    'Uploading' => 'Загрузка',
    // Upload: сообщения / текст
    'Unable to load files' => 'Невозможно загрузить файлы компонента.',
    'File "{0}" has been uploaded successfully' => 'Файл "{0}" успешно загружен.',

    // Grid: панель инструментов
    'Add' => 'Добавить', 
    'Adding an component' => 'Добавление компонента',
    'Add "{0}"' => 'Добавить "{0}"',
    'Deleting selected components' => 'Удаление выделенных компонентов',
    'Delete all components' => 'Удаление всех компонентов',
    // Grid: контекстное меню записи
    'Edit menu' => 'Редактировать',
    'Component items' => 'Элементы компонента',
    // Grid: столбцы
    'Name' => 'Название',
    'Description' => 'Описание',
    'Component identifier' => 'Идентификатор компонента',
    'Go to adding / editing component items' => 'Перейти к добавлению / редактированию элементов компонента',
    'Type' => 'Вид',
    'Component type' => 'Вид медиагалереи',
    'Published' => 'Опубликован',
    'The component has been published' => 'Компонент опубликован',
    'Author' => 'Автор',
    'yes' => 'да',
    'no' => 'нет',
    // Grid: сообщения / заголовки
    'Deleting a component' => 'Удаление компонента',
    'Publication' => 'Опубликование',
    'Hide' => 'Скрыть',
    // Grid: сообщения / текст
    '{0} "{1}" - unpublished' => '{0} "{1}" - не опубликован.',
    '{0} "{1}" - published' => '{0} "{1}" - опубликован.',
    'Unable to perform action, one of the plugins is missing' 
        => 'Невозможно выполнить действие, отсутствует один из плагинов медиагалереи.',
    'Error deleting folder "{0}" (no access), component "{1}"' 
        => 'Ошибка удаления папки "{0}" (нет доступа), альбом "{1}".',
    'Folders ({0} of {1}) containing component files were partially deleted, but component records remain' 
        => 'Папки ({0} из {1}) с файлами компонентов частично удалены, но записи компонентов остались.',

    // ItemsGrid: сообщения / текст
    'Unable to show gallery items (parameter error)' => 'Невозможно показать элементы компонента (ошибка параметра)',
    'Unable to get component item (parameter error)' => 'Невозможно получить элемент компонента (ошибка параметра)',
    'Error deleting file "{0}" (no access)' 
        => 'Ошибка удаления файла "{0}" (нет доступа).',
    'Component images ({0} of {1}) have been partially removed, but component entries remain' 
        => 'Изображения ({0} из {1}) компонента частично удалены, но записи компонента остались.'
];
