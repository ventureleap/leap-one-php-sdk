<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Audit\Transaction\TransactionInterface;

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

    public function __construct(TransactionHydratorInterface $hydrator, TransactionProcessorInterface $processor)
    {
        $this->hydrator = $hydrator;
        $this->processor = $processor;
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
