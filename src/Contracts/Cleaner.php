<?php

namespace Phpins\Contracts;

abstract class Cleaner
{
    /**
     * Модифицирует содержимое файла
     */
    abstract public function clean(string $clearContent) : string;
}
