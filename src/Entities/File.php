<?php

namespace Phpins\Entities;

use Phpins\Cleaners\RemoveComment1;
use Phpins\Cleaners\RemoveComment2;
use Phpins\Cleaners\RemoveComment3;
use Phpins\Cleaners\RemoveString1;
use Phpins\Cleaners\RemoveString2;

class File
{
    /**
     * Путь к файлу
     * @var string
     */
    public $path;

    /**
     * Содержимое файла
     * @var string
     */
    public $content;

    /**
     * Содержимое файла прошедшее мутации
     * @var string
     */
    public $clearContent;

    /**
     * Файл является объектом
     * @var bool
     */
    public $isObject;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->content = file_get_contents($path);
        $this->clearContent = $this->content;

        // Определяем содержит ли файл объект по регуляркам
        $this->isObject = preg_match(
            '/^(abstract class|class|trait|interface)[ ]+[A-Z]{1}[a-zA-Z]+/m',
            $this->clearContent
        ) != 0;

        // модифицируем файл согласно сиписку мутаторов
        $cleaners = [
            RemoveComment1::class,
            RemoveComment2::class,
            RemoveComment3::class,
            RemoveString1::class,
            RemoveString2::class,
        ];
        foreach ($cleaners as $cleaner) {
            $this->clearContent = (new $cleaner)->clean($this->clearContent);
        }
    }
}
