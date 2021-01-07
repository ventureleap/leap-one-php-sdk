<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Audit\Transaction\TransactionInterface;

interface TransactionHydratorInterface
{
    public function hydrate(TransactionInterface $transaction): void;
}
