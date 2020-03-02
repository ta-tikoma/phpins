<?php
namespace App\Entities;

use App\Entities\Dummy;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable;
use SortableInterface;

class INotForgottenAddUseThisClassTestSubject extends Model implements PaginableInterface, SortableInterface
{
    use CompareLock;

    public $UCN;

    public function recognition(
        RecognitionDtoFactory $recognitionDtoFactory
    ) : RecognitionDto {
        return $recognitionDtoFactory->createWithId();
    }

    public function findByDto() : ?DealerCenter
    {
        $job = new Dummy();
        $job->run();
        return null;
    }
}
