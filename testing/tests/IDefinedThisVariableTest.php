<?php

use Phpins\Entities\Error;
use Phpins\Validators\IDefinedThisVariable\IDefinedThisVariable;

class IDefinedThisVariableTest extends TestCase
{
    public function test()
    {
        $validator = new IDefinedThisVariable();
        $this->validatorCheck($validator, 'IDefinedThisVariableTestSubject.php');

        // var_dump($this->getErrors());

        $this->equalsErrors([
            [Error::TYPE_ERROR, 'Variable "fixValue" not defined'],     // в методе используется переменная которая не определена
            [Error::TYPE_ERROR, 'Variable "testsdk" not defined'],      // переменная в строке не определена
            [Error::TYPE_WARNING, 'Variable "hash" not use'],           // переменная из параметров метода и не используется
            [Error::TYPE_WARNING, 'Variable "numberWithFix" not use'],  // переменная внутри метода и не используется
            // [Error::TYPE_ERROR, 'Variable "log" not defined'],       // переменная которая используется в своем определении
            // [Error::TYPE_ERROR, 'Variable "numberInt" not defined'], // переменная что не передана в метод
        ]);
    }
}
