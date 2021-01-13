<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Doctrine;

abstract class AbstractService implements ServiceInterface
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
