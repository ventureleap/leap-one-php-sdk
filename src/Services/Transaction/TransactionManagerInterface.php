<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Transaction;

use VentureLeap\LeapOnePhpSdk\Model\Transaction\TransactionInterface;

interface TransactionManagerInterface
{
    public function populate(TransactionInterface $transaction): void;

    public function process(TransactionInterface $transaction): void;
}
