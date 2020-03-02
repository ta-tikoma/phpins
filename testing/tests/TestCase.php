<?php

use PHPUnit\Framework\TestCase as BaseTestCase;
use Phpins\Contracts\Validator;
use Phpins\Entities\File;

abstract class TestCase extends BaseTestCase
{
    private $errors;

    /**
     * Доступк к ошибка валидатора
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Запускает валидатор с указанным файлом
     * и записывает его результат в errors
     * @param  Validator $validator [description]
     * @param  string    $fileName  [description]
     */
    public function validatorCheck(Validator $validator, string $fileName) : void
    {
        $file = new File(__DIR__.'/../samples/'.$fileName);

        $validator->check($file);
        $this->errors = $validator->getErrors();
    }

    /**
     * Проверяет наличие ошибки
     * @param  string  $type        [description]
     * @param  string  $description [description]
     */
    public function hasError(string $type, string $description) : void
    {
        $isHas = false;
        foreach ($this->errors as $error) {
            if (($error->type == $type) &&
                (strcmp($error->description, $description) === 0)
            ) {
                $isHas = true;
                break;
            }
        }
        $this->assertTrue($isHas);
    }

    /**
     * Проверяет наличие ряда ошибок
     * @param  array   $errors [description]
     * @return boolean         [description]
     */
    public function hasErrors(array $errors) : void
    {
        foreach ($errors as $error) {
            $this->hasError($error[0], $error[1]);
        }
    }

    /**
     * Проверяет наличие ряда ошибок
     * @param  array   $errors [description]
     * @return boolean         [description]
     */
    public function equalsErrors(array $errors) : void
    {
        $this->hasErrors($errors);
        $this->assertEquals(count($errors), count($this->getErrors()));
    }
}
