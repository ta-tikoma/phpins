<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;

class IDefinedThisVariableTestSubject extends Controller
{
    private $message;

    public function deleteChannel(string $number, string $hash)
    {
        /** @var User $numberInt */
        $numberInt = intval($number);
        $numberWithFix = $numberInt + $fixValue;
        $log = $log + 1;

        echo "easkjd a;$testsdk; dasdasd";

        $users = User::whereHas('related_goals', function ($query) use ($number) {
            $query->whereIn('number', $number);
            $query->whereIn('numberInt', $numberInt);
        });

        return $users;
    }
}
