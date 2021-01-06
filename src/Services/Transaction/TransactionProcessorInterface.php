<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Transaction\TransactionInterface;

interface TransactionProcessorInterface
{
    public function process(TransactionInterface $transaction): void;
}
