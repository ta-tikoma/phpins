<?php

namespace Phpins\Contracts;

use Phpins\Entities\File;

abstract class Validator
{
    /**
     * Список ошибок
     * @var array
     */
    protected $errors = [];

    /**
     * Сокращенное название валидатора
     * @var string
     */
    protected $shortName = '';

    /**
     * Запуск проверки файла
     */
    abstract public function check(File $file);

    public function getShortName() : string
    {
        return $this->shortName;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }
}
