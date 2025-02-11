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
    '{description}' => 'Управления альбомами медиагалереи на сайте',
    '{permissions}' => [
        'any'       => ['Полный доступ', 'Просмотр и внесение изменений в альбом'],
        'view'      => ['Просмотр', 'Просмотр альбома'],
        'read'      => ['Чтение', 'Чтение альбома'],
        'add'       => ['Добавление', 'Добавление альбома'],
        'edit'      => ['Изменение', 'Изменение альбома'],
        'delete'    => ['Удаление', 'Удаление альбома'],
        'clear'     => ['Очистка', 'Удаление всех альбома']
    ],

    // Upload: сообщения / заголовки
    'Uploading' => 'Загрузка',
    // Upload: сообщения / текст
    'Unable to load files' => 'Невозможно загрузить файлы альбома.',
    'File "{0}" has been uploaded successfully' => 'Файл "{0}" успешно загружен.',

    // Grid: панель инструментов
    'Add' => 'Добавить', 
    'Adding an album' => 'Добавление альбома',
    'Add "{0}"' => 'Добавить "{0}"',
    'Deleting selected albums' => 'Удаление выделенных альбомов',
    'Delete all albums' => 'Удаление всех альбомов',
    // Grid: контекстное меню записи
    'Edit menu' => 'Редактировать',
    'Album items' => 'Элементы альбома',
    // Grid: столбцы
    'Name' => 'Название',
    'Description' => 'Описание',
    'Album identifier' => 'Идентификатор альбома',
    'Go to adding / editing album items' => 'Перейти к добавлению / редактированию элементов альбома',
    'Type' => 'Вид',
    'Album type' => 'Вид альбома',
    'Published' => 'Опубликован',
    'The album has been published' => 'Альбом опубликован',
    'Author' => 'Автор',
    'yes' => 'да',
    'no' => 'нет',
    // Grid: сообщения / заголовки
    'Deleting a album' => 'Удаление альбома',
    'Publication' => 'Опубликование',
    'Hide' => 'Скрыть',
    // Grid: сообщения / текст
    '{0} "{1}" - unpublished' => '{0} "{1}" - не опубликован.',
    '{0} "{1}" - published' => '{0} "{1}" - опубликован.',
    'Unable to perform action, one of the plugins is missing' 
        => 'Невозможно выполнить действие, отсутствует один из плагинов медиагалереи.',
    'Error deleting folder "{0}" (no access), album "{1}"' 
        => 'Ошибка удаления папки "{0}" (нет доступа), альбом "{1}".',
    'Folders ({0} of {1}) containing album files were partially deleted, but album records remain' 
        => 'Папки ({0} из {1}) с файлами альбомов частично удалены, но записи альбомов остались.',

    // ItemsGrid: сообщения / текст
    'Unable to show gallery items (parameter error)' => 'Невозможно показать элементы альбома (ошибка параметра)',
    'Unable to get album item (parameter error)' => 'Невозможно получить элемент альбома (ошибка параметра)',
    'Error deleting file "{0}" (no access)' 
        => 'Ошибка удаления файла "{0}" (нет доступа).',
    'Album images ({0} of {1}) have been partially removed, but album entries remain' 
        => 'Изображения ({0} из {1}) альбома частично удалены, но записи альбома остались.',
];
