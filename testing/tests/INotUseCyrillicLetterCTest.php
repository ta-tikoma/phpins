<?php

use Phpins\Validators\INotUseCyrillicLetterC\INotUseCyrillicLetterC;

class INotUseCyrillicLetterCTest extends TestCase
{
    // тест на кирилицу в переменных
    public function test()
    {
        $validator = new INotUseCyrillicLetterC();
        $this->validatorCheck($validator, 'INotUseCyrillicLetterСTestSubject.php');

        // die(print_r($this->getErrors(), true));

        $this->assertEquals(2, count($this->getErrors()));
    }
}
