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
abstract class PreviewExtractor extends AbstractPreviewExtractor
{

    private $url = '';
    private $params_arr = [];

    /**
     * Конструктор.
     *
     * @param string $url URL страницы, на которой может быть обнаружена статья
     * @param array $params_arr Необязательный массив параметров гибкой настройки
     */
    public function __construct(string $url, array $params_arr = [])
    {
        $this->url = $url;
        $this->params_arr = $params_arr;
    }

    /**
     * Возвращает анонс статьи в виде простого текста.
     *
     * @return string
     */
    public function getPreview(): string
    {
        return HtmlNormalizer::getInstance()
            ->setHtml(UrlDownloader::download($this->url))
            ->getResult();
    }


}
