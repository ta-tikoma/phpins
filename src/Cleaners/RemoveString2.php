<?php

namespace Phpins\Cleaners;

use Phpins\Contracts\Cleaner;
use Phpins\Helpers\PregMatchAll;

/**
 * Двойные ковычки
 */
class RemoveString2 extends Cleaner
{
    public function clean(string $clearContent) : string
    {
        return preg_replace_callback(
            '/\"[^\"\n]*\"/m',
            function ($matches) {
                // находим все переменные в строке
                $variablesInString = PregMatchAll::resultMerge(
                    $matches[0],
                    '/(\$[a-zA-Z0-9_]+)/m'
                );

                // создаем строку только из переменных
                $result = '';
                foreach ($variablesInString as $variableInString) {
                    list($variable, $position) = $variableInString;
                    $result .= str_repeat(" ", $position).$variable;
                }

                $result .= str_repeat(" ", strlen($matches[0]) - strlen($result));

                return $result;
            },
            $clearContent
        );
    }
}
