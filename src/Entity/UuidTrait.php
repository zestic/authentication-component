<?php
declare(strict_types=1);

namespace Zestic\Authentication\Entity;

use Ramsey\Uuid\Uuid;

trait UuidTrait
{
    /** @var Uuid */
    protected $id;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getIdAsBinary(): string
    {
        return $this->id->getBytes();
    }

    public function getIdAsString(): string
    {
        return $this->id->gethex()->toString();
    }
}
