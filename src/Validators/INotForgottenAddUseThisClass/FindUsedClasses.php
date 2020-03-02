<?php

namespace Phpins\Validators\INotForgottenAddUseThisClass;

use Phpins\Helpers\PregMatchAll;

trait FindUsedClasses
{
    /**
     * Все использованные классы
     */
    private function getUsedClasses(string $clearContent) : array
    {
        // проверяем контент только от начала класса
        $clearContent = PregMatchAll::afterLast(
            '/^(class|abstract class|trait|interface) '.self::CLASS_NAME_REGEX.'/m',
            $clearContent
        );

        // находим по регуляркам все использованные классы
        $classUses = PregMatchAll::resultMerge(
            $clearContent,
            [
                '/new ('.self::CLASS_NAME_REGEX.')/m',                              // создание класса
                '/: [\?]{0,1}('.self::CLASS_NAME_REGEX.')/m',                       // тип возвращаемый методом
                '/[^\\\\A-Za-z]{1}('.self::CLASS_NAME_REGEX.') \$[a-zA-Z0-9_]+/m',  // тип переменной
                '/[^\\\\A-Za-z]{1}('.self::CLASS_NAME_REGEX.')::/m',                // статически метод или переменная
                '/extends ('.self::CLASS_NAME_REGEX.')/m',                          // наследвоание
                '/instanceof ('.self::CLASS_NAME_REGEX.')/m',                       // сверка типа
                '/\@[a-zA-Z0-9_]+ ('.self::CLASS_NAME_REGEX.')/m'                   // когда класс указан в док. комментариях
            ]
        );

        // места с несколькими клссами
        $classesUses = PregMatchAll::resultMerge(
            $clearContent,
            [
                '/implements ([^{]+){/m',                           // реализация
                '/^[ ]*use ([^;]+);/m',                             // трейты
            ]
        );

        foreach ($classesUses as $classesUse) {
            list($classesString, $position) = $classesUse;
            $classes = [];
            preg_match_all(
                '/('.self::CLASS_NAME_REGEX.')/m',
                $classesString,
                $classes,
                PREG_OFFSET_CAPTURE
            );
            if (!isset($classes[1])) {
                continue;
            }
            foreach ($classes[1] as $class) {
                $classUses[] = [$class[0], $position + $class[1]];
            }
        }

        // удаляем те перед которым стоит слеш
        return $classUses;
    }
}
