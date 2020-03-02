<?php

namespace Phpins;

use Phpins\Entities\File;
use Phpins\Validators\IDefinedThisVariable\IDefinedThisVariable;
use Phpins\Validators\INotForgottenAddUseThisClass\INotForgottenAddUseThisClass;
use Phpins\Validators\INotUseCyrillicLetterC\INotUseCyrillicLetterC;

class PHPImNotStupid
{
    private $validatorNames = [
        INotForgottenAddUseThisClass::class,
        IDefinedThisVariable::class,
        INotUseCyrillicLetterC::class,
    ];

    /**
     * Проверка файла
     */
    public function validate(string $filePath, array $exclude = [])
    {
        $file = new File($filePath);

        // проход по всем валидаторам
        foreach ($this->validatorNames as $validatorName) {
            // создаем валидатор
            $validator = new $validatorName();

            // исключаем валидатор если он находится в списке исключения
            if (in_array($validator->getShortName(), $exclude)) {
                continue;
            }

            // запускаем проверку
            $validator->check($file);

            // выводим ошибки
            foreach ($validator->getErrors() as $error) {
                $error->print($file);
            }
        }
    }
}
