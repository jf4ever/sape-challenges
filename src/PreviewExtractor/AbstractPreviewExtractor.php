<?php

/**
 * Sape – система для привлечения клиентов (https://www.sape.ru/)
 *
 * @copyright Copyright (c) 2016 SAPE Limited
 */

namespace Sape\Challenge\PreviewExtractor;

/**
 * Абстрактный базовый класс для получения анонса статьи.
 *
 * Задача состоит в разработке функционала, способного обнаружить статью на странице по заданному URL-у и вырезать из
 *      этой статьи очищенный от какого-либо форматирования некоторый кусок текста, который можно считать анонсом.
 */
abstract class AbstractPreviewExtractor
{
    /**
     * Конструктор.
     *
     * @param string $url URL страницы, на которой может быть обнаружена статья
     * @param array $params_arr Необязательный массив параметров гибкой настройки
     */
    abstract public function __construct($url, $params_arr = array());

    /**
     * Возвращает анонс статьи в виде простого текста.
     *
     * @return string
     */
    abstract public function getPreview();
}
