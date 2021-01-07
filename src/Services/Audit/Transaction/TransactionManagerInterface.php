<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Audit\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Audit\Transaction\TransactionInterface;

interface TransactionManagerInterface
{
    public function populate(TransactionInterface $transaction): void;

    public function process(TransactionInterface $transaction): void;
}
