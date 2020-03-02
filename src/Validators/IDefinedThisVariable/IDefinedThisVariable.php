<?php

namespace Phpins\Validators\IDefinedThisVariable;

use Phpins\Contracts\Validator;
use Phpins\Entities\Error;
use Phpins\Entities\File;
use Phpins\Helpers\PregMatchAll;

class IDefinedThisVariable extends Validator
{
    protected $shortName = 'idtv';

    public function check(File $file)
    {
        // валидатор только для классов
        if (!$file->isObject) {
            return;
        }

        $functionBegins = $this->functionBegins($file->clearContent);

        // если функций не нашли то уходим
        if (count($functionBegins) == 0) {
            return;
        }

        // режем файл на функции и проверяем переменные только внутри них
        for ($i = 0; $i < count($functionBegins); $i++) {
            // начало функции
            $positionFrom = $functionBegins[$i][1];
            // начало следующей или конеф файла
            if (isset($functionBegins[$i + 1])) {
                $positionTo = $functionBegins[$i + 1][1];
            } else {
                $positionTo = strlen($file->clearContent);
            }

            // содержимое функции
            $functionContent = substr($file->clearContent, $positionFrom, $positionTo - $positionFrom);

            $this->checkFunction($functionContent, $positionFrom);
        }
    }

    /**
     * Проверка одной содержимого функции
     */
    private function checkFunction(string $functionContent, int $positionFrom)
    {
        // находим определения переменных
        $variableDefines = $this->variableDefines($functionContent);

        // находим использование функций
        $variableUses = $this->variableUses($functionContent);

        // массив для учета использованных переменных
        $uses = [];

        /**
         * в используемые переменные поподают их определения удаляем их
         */
        foreach ($variableUses as $key => $variableUse) {
            list($variableU, $positionU) = $variableUse;
            foreach ($variableDefines as $variableDefine) {
                list($variableD, $positionD) = $variableDefine;
                if (($variableU == $variableD) && ($positionU == $positionD)) {
                    unset($variableUses[$key]);
                }
            }
        }

        /**
         * проверямем что использование было ниже чем определение
         */
        foreach ($variableUses as $variableUse) {
            list($variableU, $positionU) = $variableUse;
            // пропускаем
            if ($variableU == 'this') {
                continue;
            }

            // ищем позицию определения этой переменной
            $positionDefine = null;
            foreach ($variableDefines as $variableDefine) {
                list($variableD, $positionD) = $variableDefine;
                if ($variableU !== $variableD) {
                    continue;
                }

                if ($positionU > $positionD) {
                    $positionDefine = $positionD;
                }
            }

            // если позиция не определена значит выводим ошибку
            if (is_null($positionDefine)) {
                $this->errors[] = new Error(
                    $positionFrom + $positionU,
                    Error::TYPE_ERROR,
                    'Variable "'.$variableU.'" not defined'
                );
            }


            $uses[] = $variableU;
        }

        /**
         * посматриваем все ли переменные были использованы
         */
        foreach ($variableDefines as $variableDefine) {
            list($variableD, $positionD) = $variableDefine;
            if (in_array($variableD, $uses)) {
                continue;
            }

            $this->errors[] = new Error(
                $positionFrom + $positionD,
                Error::TYPE_WARNING,
                'Variable "'.$variableD.'" not use'
            );
        }
    }

    /**
     * определение переменных
     * @param  string $content
     * @return array
     */
    private function variableDefines(string $content)
    {
        $variableDefines = PregMatchAll::resultMerge(
            $content,
            '/[ \(]{1}\$([a-zA-Z0-9_]+)[ ]*= /m'
        );
        $paramsDefineds = PregMatchAll::resultMerge(
            $content,
            [
                '/function [^\(]*\(([^)]+)\)/m',
                '/foreach[^\(]*\(.* as ([^)]+)\)/m',
                '/[^>]list\(([^)]+)\)/m', // @todo может быть стоит перед каждой регулярой установить [^>] ?
                '/catch[^\(]*\(([^)]+)\)/m',
            ]
        );
        foreach ($paramsDefineds as $paramsDefined) {
            list($params, $position) = $paramsDefined;
            $variableFromParams = null;
            preg_match_all(
                '/\$([a-zA-Z0-9_]+)/m',
                $params,
                $variableFromParams,
                PREG_OFFSET_CAPTURE
            );
            foreach ($variableFromParams[1] as $variableFromParam) {
                $variableDefines[] = [$variableFromParam[0], $position + $variableFromParam[1]];
            }
        }
        // сортируем по позикции
        usort($variableDefines, function ($first, $second) {
            list(,$positionF) = $first;
            list(,$positionS) = $second;
            return $positionF > $positionS;
        });
        return $variableDefines;
    }

    /**
     * использование переменных
     * @param  string $content
     * @return array
     */
    private function variableUses(string $content)
    {
        return PregMatchAll::resultMerge(
            $content,
            '/[^:]{1}\$([a-zA-Z0-9_]+)/m'
        );
    }

    /**
     * Определяем начала всех функций в файле
     * @param  string $fileContent
     * @return array
     */
    private function functionBegins(string $fileContent)
    {
        $functionBegins = null;
        preg_match_all(
            '/function [a-zA-Z0-9_]+\([^\)]*\)[^{]+{/m',
            $fileContent,
            $functionBegins,
            PREG_OFFSET_CAPTURE
        );
        if (!is_null($functionBegins) && count($functionBegins)) {
            return $functionBegins[0];
        }
        return [];
    }
}
