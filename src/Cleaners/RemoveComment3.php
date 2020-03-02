<?php

namespace Phpins\Cleaners;

use Phpins\Contracts\Cleaner;

/* для таких комментариев */
class RemoveComment3 extends Cleaner
{
    public function clean(string $clearContent) : string
    {
        return preg_replace_callback(
            '/\/\*.*\*\//m',
            function ($matches) {
                $value = $matches[0];

                $classInComment = null;
                // находим определения класса в комментариях
                if (preg_match_all(
                    '/\@[a-z]+ ([A-Z]{1}[a-zA-Z0-9_]+)/m',
                    $value,
                    $classInComment,
                    PREG_OFFSET_CAPTURE
                )) {
                    $match = $classInComment[0][0];
                    return str_repeat(" ", $match[1]) // до класса
                        .$match[0] // класс
                        .str_repeat(" ", strlen($value) - $match[1] - strlen($match[0])) // то что после
                        ;
                }
                return str_repeat(" ", strlen($value));
            },
            $clearContent
        );
    }
}
