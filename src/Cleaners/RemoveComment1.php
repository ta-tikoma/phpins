<?php

namespace Phpins\Cleaners;

use Phpins\Contracts\Cleaner;

// для таких комментариев
class RemoveComment1 extends Cleaner
{
    public function clean(string $clearContent) : string
    {
        return preg_replace_callback(
            '/\/\/.*$/m',
            function ($matches) {
                return str_repeat(" ", strlen($matches[0]));
            },
            $clearContent
        );
    }
}
