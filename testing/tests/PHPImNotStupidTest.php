<?php

use Phpins\PHPImNotStupid;

class PHPImNotStupidTest extends TestCase
{
    public function test()
    {
        try {
            $phpins = new PHPImNotStupid();
            $phpins->validate(__DIR__.'/../samples/Dummy.php');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            var_dump($e);
            $this->assertTrue(false);
        }
    }
}
