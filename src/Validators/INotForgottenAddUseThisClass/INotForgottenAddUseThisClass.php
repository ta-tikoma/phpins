<?php

namespace Phpins\Validators\INotForgottenAddUseThisClass;

use Phpins\Contracts\Validator;
use Phpins\Entities\Error;
use Phpins\Entities\File;

/**
 * Проверяем подключение классов
 * 1 наличие всех требуемых подключений классов
 * 2 дублирование подключений
 * 3 лишние подключения
 */
class INotForgottenAddUseThisClass extends Validator
{
    use FindDefinedClasses, FindUsedClasses;

    const CLASS_NAME_REGEX = '[A-Z]{1}[a-zA-Z0-9_]+';

    protected $shortName = 'infautc';

    public function check(File $file)
    {
        // валидатор только для классов
        if (!$file->isObject) {
            return;
        }

        // забираем все подключенные класы
        $definedClasses = $this->getDefinedClasses($file->path, $file->clearContent);

        // забираем все классы что использовались
        $usedClasses = $this->getUsedClasses($file->clearContent);

        // запоминаем использованные классы
        $uses = [];

        // проверка на подкючение
        foreach ($usedClasses as $usedClass) {
            if (array_key_exists($usedClass[0], $definedClasses)) {
                $uses[$usedClass[0]] = $usedClass[0];
                continue;
            }

            $this->errors[] = new Error(
                $usedClass[1],
                Error::TYPE_ERROR,
                'Add use class "'.$usedClass[0].'"'
            );
        }

        // проверка на неиспользованные подключения
        foreach ($definedClasses as $className => $offset) {
            if (isset($uses[$className]) || is_null($offset)) {
                continue;
            }

            $this->errors[] = new Error(
                $offset,
                Error::TYPE_WARNING,
                'Class not use "'.$className.'"'
            );
        }
    }
}
