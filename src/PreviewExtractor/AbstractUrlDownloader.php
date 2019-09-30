<?php

namespace Sape\Challenge\PreviewExtractor;

/**
 * Абстактный класс для скачивания страниц из интернета по сети
 *
 * Class AbstractHtmlPageDownloader
 * @package Sape\Challenge\PreviewExtractor
 */
abstract class AbstractUrlDownloader
{
    /**
     * подготовка url
     * @param string $url
     * @return string
     */
    private static function prepareUrl(string $url):string{}

    /**
     * валидация url
     * @param $url
     */
    private static function checkUrl($url):void{}

    /**
     * скачивание url
     * @param string $url
     * @return string
     */
    public static function download(string $url):string{}
}