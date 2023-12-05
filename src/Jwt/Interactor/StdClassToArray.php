<?php
declare(strict_types=1);

namespace Zestic\Authentication\Jwt\Interactor;

use stdClass;

final class StdClassToArray
{
    public function __invoke(stdClass $object): array
    {
        return json_decode(json_encode($object), true);
    }
}
