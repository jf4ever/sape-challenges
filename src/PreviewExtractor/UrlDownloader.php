<?php

namespace Sape\Challenge\PreviewExtractor;

/**
 * Скачивание кода страницы по url
 *
 * Class HtmlPageDownloader
 * @package Sape\Challenge\PreviewExtractor
 */
class UrlDownloader extends AbstractUrlDownloader
{

    /**
     * Скачивает только url протоколу http или https
     * @var bool
     */
    private static $http_only = true;

    /**
     * Подготовка url адреса
     * - обрезка пробелов с концов
     * - преобразование в url encoded
     *
     * @param string $url
     * @return string
     */
    private static function prepareUrl(string $url):string
    {
        return urlencode(trim($url));
    }

    /**
     * Проверка на валидность url как веб адреса
     * - валидация var_filter FILTER_VALIDATE_URL
     * - проверяем схему на валидность ( http или https ) если переменная класа $http_only истина
     *
     * @param $url
     * @throws Exception
     */
    private static function checkUrl($url):void
    {
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            Throw new Exception('url ' . htmlspecialchars($url) . ' не похож на валидный');
        }

        if(self::$http_only) {
            if (strpos('https://', $url) !== 0 && strpos('http://', $url) !== 0) {
                Throw new Exception('url ' . htmlspecialchars($url) . ' не похож на валидный');
            }
        }
    }

    /**
     * Загрузка старинцы по url
     * - подготавливаем url self::prepareUrl
     * - проверяем на валидность self::checkUrl
     * - скачиваем
     * - возвращаем результат
     *
     * @param string $url
     * @return string
     * @throws Exception
     */
    public static function download(string $url):string
    {
        $return_text = '';

        $url = self::prepareUrl($url);
        self::checkUrl($url);

        $read_handle = fopen($url, 'rb');
        if (!$read_handle) {
            Throw new Exception('Не возможно получить url ' . htmlspecialchars($url));
        }
        while (($line = fgets($read_handle)) !== false) {
            $return_text .= $line;
        }
        fclose($read_handle);

        if(trim($return_text) == ''){
            Throw new Exception('Полученная html страница путса');
        }

        return $return_text;
    }

}