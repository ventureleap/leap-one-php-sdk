<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(string $name, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
