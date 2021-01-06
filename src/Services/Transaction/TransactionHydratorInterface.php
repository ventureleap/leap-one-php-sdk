<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Transaction\TransactionInterface;

interface TransactionHydratorInterface
{
    public function hydrate(TransactionInterface $transaction): void;
}
