<?php

namespace Phpins\Cleaners;

use Phpins\Contracts\Cleaner;

/**
 * Одинарные ковычки
 */
class RemoveString1 extends Cleaner
{
    public function clean(string $clearContent) : string
    {
        return preg_replace_callback(
            '/\'[^\'\n]*\'/m',
            function ($matches) {
                return str_repeat(' ', strlen($matches[0]));
            },
            $clearContent
        );
    }
}
