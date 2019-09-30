<?php


namespace Sape\Challenge\PreviewExtractor;

/**
 * Класс одиночка для работы с html страницей
 * - получение наиболее подходящего для статьи текста
 * - возврат подходящего текста
 *
 * Class HtmlNormalizer
 * @package Sape\Challenge\PreviewExtractor
 */
class HtmlNormalizer
{
    /**
     * Конечная кодировка текста ( как правило должна быть кодировкой текущего проекта )
     * @var string
     */
    private $localEncoding = 'UTF-8';
    /**
     * Длинна текста, который должен быть статьей
     * @var int
     */
    private $articleMinLength = 300;
    /**
     * Длинна превью текста строки
     * @var int
     */
    private $previewLength = 200;
    /**
     * html страница
     * @var string
     */
    private $html = '';
    /**
     * параметры подготовки html
     * - абстактные ))
     * @var array
     */
    private $params = [
        'ignore_encoding' => false
    ];
    /**
     * Подготовленная и обрезанная статья для результата
     * @var string
     */
    private $articleHtml = '';

    /**
     * singleton
     * @var
     */
    private static $_instance;

    /**
     * Закрываем конструтор
     * HtmlNormalizer constructor.
     */
    private function __construct(){}

    /**
     * закрываем clone
     */
    private function __clone(){}

    /**
     * закрываем сериализацию
     */
    private function __serialize(){}

    /**
     * Получение экземпляра класса одиночки HtmlNormalizer
     * @return HtmlNormalizer
     */
    public static function getInstance(): HtmlNormalizer
    {
        if(is_null(self::$_instance)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Установка локальной кодировки
     * @param string $encoding
     * @return HtmlNormalizer
     */
    public function setLocalEncoding(string $encoding): HtmlNormalizer
    {
        $this->localEncoding = $encoding;
        return $this;
    }

    /**
     * Получение устаноленной локальной кодировки
     * @return string
     */
    public function getLocalEncoding():string
    {
        return $this->localEncoding;
    }

    /**
     * Установка html кода для работы
     * @param string $html
     * @return HtmlNormalizer
     */
    public function setHtml(string $html): HtmlNormalizer
    {
        $this->html = $html;
        return $this;
    }

    /**
     * Получение html кода с которым работаем
     * @return string
     */
    public function getHtml():string
    {
        return $this->html;
    }

    /**
     * Установка параметров
     * @param array $params
     * @return HtmlNormalizer
     */
    public function setParams(array $params): HtmlNormalizer
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Получение параметров
     * @return array
     */
    public function getParams():array
    {
        return $this->params;
    }

    /**
     * подготовка скачанного html ( преобразование кодировки, в локальную для корректной работы )
     */
    private function prepareHtmlEncoding(): void
    {
        $encoding = mb_detect_encoding($this->html, mb_list_encodings(), true);
        if($encoding === false){
            Throw new Exception('Can\'t detect html encoding. Broken html?');
        }
        if ($encoding !== $this->localEncoding){
            $this->html = mb_convert_encoding($this->html, $this->localEncoding, $encoding);
        }
    }

    /**
     * Обрезка полченной статьи до определенной длинны
     */
    private function cutArticleTextByWords(): void
    {
        $substring_limited = substr($this->articleHtml, 0, $this->previewLength);        //режем строку от 0 до limit
        $this->articleHtml = substr($substring_limited, 0, strrpos($substring_limited, ', ' ));
    }

    /**
     * Угадываем в тексте статью
     */
    private function guessArticleText():void
    {
        $alternativeText = '';
        $lastAlternativeTextLength = 0;
        $foundText = '';
        $html = preg_replace(['@^.*?<body>(.*?)$@siu', '@^(.*?)</body>.*?$@siu'], ['$1', '$1']);
        $html = strip_tags($html, ['<div><article>']);
        $matches = [];
        preg_match_all('@[,\w\s\.\(\)\d]+@siu', $html, $matches);
        foreach($matches as $k => $match){
            $len = strlen($match);
            $match = trim($match);
            if($len > $lastAlternativeTextLength){
                $alternativeText = trim($match);
            }
            if($len >= $this->articleMinLength){
                $foundText = $match;
                break;
            }
        }
        if($foundText === ''){
            $foundText = $alternativeText;
        }
        if($foundText === ''){
            Throw new Exception('Полученный текст пустой');
        }
        $this->articleHtml = $foundText;
    }

    /**
     * подготавливаем заданный html для парсинга
     * @return HtmlNormalizer
     */
    private function prepareHtml(): HtmlNormalizer
    {
        if($this->html === ''){
            Throw new Exception('Empty html data; use setHtml(string $html) with valid html code!');
        }
        if(!isset($this->params['ignore_encoding']) || $this->params['ignore_encoding'] !== true){
            $this->prepareHtmlEncoding();
        }
        foreach($this->params as $param){
            // @TODO Do something with $this->html
        }

        return $this;
    }

    /**
     * получение найденной и обрезанной статьи
     * @return string
     */
    public function getResult(): string
    {
        $this->prepareHtml();
        $this->guessArticleText();
        $this->cutArticleTextByWords();
        return $this->articleHtml;
    }

}