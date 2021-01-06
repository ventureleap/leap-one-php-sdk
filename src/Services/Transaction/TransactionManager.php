<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Transaction\TransactionInterface;
use VentureLeap\LeapOnePhpSdk\Services\Doctrine\DoctrineProvider;

class TransactionManager implements TransactionManagerInterface
{
    /**
     * @var TransactionProcessorInterface
     */
    private $processor;

    /**
     * @var TransactionHydratorInterface
     */
    private $hydrator;

    public function __construct(DoctrineProvider $provider)
    {
        $this->processor = new TransactionProcessor($provider);
        $this->hydrator = new TransactionHydrator($provider);
    }

    public function populate(TransactionInterface $transaction): void
    {
        $this->hydrator->hydrate($transaction);
    }

    public function process(TransactionInterface $transaction): void
    {
        $this->processor->process($transaction);
    }
}
