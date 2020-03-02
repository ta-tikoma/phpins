<?php

use Phpins\Entities\Error;
use Phpins\Validators\INotForgottenAddUseThisClass\INotForgottenAddUseThisClass;

class INotForgottenAddUseThisClassTest extends TestCase
{
    public function test()
    {
        $validator = new INotForgottenAddUseThisClass();
        $this->validatorCheck($validator, 'INotForgottenAddUseThisClassTestSubject.php');

        // die(print_r($this->getErrors(), true));

        $this->equalsErrors([
            [Error::TYPE_WARNING, 'Class not use "Authenticatable"'],       // подключение не используется
            [Error::TYPE_WARNING, 'Class not use "AuthorizableContract"'],  // подключение через as не используется
            [Error::TYPE_WARNING, 'Class in current namespace "Dummy"'],    // когда класс лежит рядом

            [Error::TYPE_ERROR, 'Add use class "PaginableInterface"'],      // реализация интерфейса
            [Error::TYPE_ERROR, 'Add use class "Model"'],                   // наследование
            [Error::TYPE_ERROR, 'Add use class "CompareLock"'],             // трейты

            [Error::TYPE_ERROR, 'Add use class "RecognitionDtoFactory"'],   // тип параметра метода
            [Error::TYPE_ERROR, 'Add use class "RecognitionDto"'],          // тип возвращаемый методом
            [Error::TYPE_ERROR, 'Add use class "DealerCenter"'],            // тип возвращаемый методом, возможно null
        ]);
    }
}
