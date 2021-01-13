<?php


namespace VentureLeap\LeapOnePhpSdk\Services\Audit\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Audit\Transaction\Transaction as BaseTransaction;
use Doctrine\ORM\EntityManagerInterface;

class Transaction extends BaseTransaction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
