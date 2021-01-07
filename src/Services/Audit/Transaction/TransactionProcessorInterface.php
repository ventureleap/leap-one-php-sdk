<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Audit\Transaction\TransactionInterface;

interface TransactionProcessorInterface
{
    public function process(TransactionInterface $transaction): void;
}
