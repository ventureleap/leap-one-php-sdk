<?php


namespace AutoMapperPlus\AutoMapperPlusBundle\src\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\SQLLogger;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpKernel\Log\Logger;
use VentureLeap\LeapOnePhpSdk\Services\Transaction\Transaction;
use VentureLeap\LeapOnePhpSdk\Services\Transaction\TransactionManager;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var TransactionManager
     */
    private $transactionManager;

    /**
     * @var ?SQLLogger
     */
    private $loggerBackup;

    public function __construct(TransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * It is called inside EntityManager#flush() after the changes to all the managed entities
     * and their associations have been computed.
     *
     * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#onflush
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $entityManager = $args->getEntityManager();
        $transaction = new Transaction($entityManager);

        // extend the SQL logger
        $this->loggerBackup = $entityManager->getConnection()->getConfiguration()->getSQLLogger();
        $auditLogger = new Logger(function () use ($entityManager, $transaction): void {
            // flushes pending data
            $entityManager->getConnection()->getConfiguration()->setSQLLogger($this->loggerBackup);
            $this->transactionManager->process($transaction);
        });

        // Initialize a new LoggerChain with the new AuditLogger + the existing SQLLoggers.
        $loggers = [$auditLogger];
        if ($this->loggerBackup instanceof LoggerChain) {
            foreach ($this->loggerBackup->getLoggers() as $logger) {
                $loggers[] = $logger;
            }
        } elseif ($this->loggerBackup instanceof SQLLogger) {
            $loggers[] = $this->loggerBackup;
        }
        $loggerChain = new LoggerChain($loggers);

        $entityManager->getConnection()->getConfiguration()->setSQLLogger($loggerChain);

        // Populate transaction
        $this->transactionManager->populate($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [Events::onFlush];
    }
}
