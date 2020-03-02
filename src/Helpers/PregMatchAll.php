<?php

namespace Phpins\Helpers;

class PregMatchAll
{
    /**
     * @param  string $content
     * @param  string|array $regexs
     * @param  array  $result
     * @return array
     */
    public static function resultMerge(string $content, $regexs, array $result = []) : array
    {
        if (is_string($regexs)) {
            $matches = [];
            preg_match_all(
                $regexs,
                $content,
                $matches,
                PREG_OFFSET_CAPTURE
            );
            return array_merge($result, $matches[1]);
        }

        foreach ($regexs as $regex) {
            $result = self::resultMerge($content, $regex, $result);
        }
        return $result;
    }

    /**
     * Заменяет все символы на пробелы тем самым делая блок текста не подходящим под валидацию
     */
    public static function replaceSymbolsToSpace(string $lines) : string
    {
        return preg_replace('/[^\n]{1}/', ' ', $lines);
    }

    /**
     * Находит последнее совпадение с регуляркой в контенте и заполняет все до него пробелами
     */
    public static function afterLast(string $regex, string $content) : string
    {
        $matches = [];
        preg_match_all(
            $regex,
            $content,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        if (isset($matches[0])) {
            $match = array_pop($matches[0]);
            $lastSymbol = $match[1] + strlen($match[0]);
            $before = substr($content, 0, $lastSymbol);
            $after = substr($content, $lastSymbol);

            return
                self::replaceSymbolsToSpace($before)
                .$after;
        }

        return $content;
    }
}
