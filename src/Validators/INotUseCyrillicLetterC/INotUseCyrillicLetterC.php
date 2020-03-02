<?php

namespace Phpins\Validators\INotUseCyrillicLetterC;

use Phpins\Contracts\Validator;
use Phpins\Entities\Error;
use Phpins\Entities\File;
use Phpins\Helpers\PregMatchAll;

/**
 * Проверяет наличие кирилицы среди названий функий переменных и прочего
 */
class INotUseCyrillicLetterC extends Validator
{
    protected $shortName = 'inuclc';

    public function check(File $file)
    {
        // находим все слова не к месту содержащие букву кирилицу
        $cyillicLetterCInWords = $this->findCyryllicSymbolsInString($file->clearContent);

        foreach ($cyillicLetterCInWords as $word) {
            list($variableU, $positionU) = $word;

            // создаем ошибку
            $this->errors[] = new Error(
                $positionU,
                Error::TYPE_ERROR,
                'Word "'.$variableU.'" has cyryllic symbol(s)'
            );
        }

        // проверяем не содержит ли путь до файла кирилицу
        $cyillicLetterCInWords = $this->findCyryllicSymbolsInString($file->path);

        if (count($cyillicLetterCInWords)) {
            // создаем ошибку
            $this->errors[] = new Error(
                0,
                Error::TYPE_ERROR,
                'Path to file has cyryllic symbol(s)'
            );
        }
    }

    private function findCyryllicSymbolsInString(string $text) : array
    {
        return PregMatchAll::resultMerge(
            $text,
            [
                '/([а-яёЁА-Я]{1}[a-zA-Z0-9]+)/m',
                '/([a-zA-Z0-9]+[а-яёЁА-Я]{1})/m',
                '/([a-zA-Z0-9]+[а-яёЁА-Я]{1}[a-zA-Z0-9]+)/m'
            ]
        );
    }
}
