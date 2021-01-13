<?php

namespace VentureLeap\LeapOnePhpSdk\Services\Doctrine;


use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;
use VentureLeap\LeapOnePhpSdk\EventSubscriber\DoctrineSubscriber;
use VentureLeap\LeapOnePhpSdk\Services\Annotation\AnnotationLoader;
use VentureLeap\LeapOnePhpSdk\Services\Audit\Transaction\TransactionManager;
use VentureLeap\LeapOnePhpSdk\Util\DoctrineHelper;
use VentureLeap\LeapOnePhpSdk\Event\LifecycleEvent;

class DoctrineProvider extends AbstractProvider
{
    /**
     * @var TransactionManager
     */
    private $transactionManager;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
        $this->transactionManager = new TransactionManager($this);
    }

    public function registerAuditingService(AuditingServiceInterface $service): ProviderInterface
    {
        parent::registerAuditingService($service);

        \assert($service instanceof AuditingService);    // helps PHPStan
        $entityManager = $service->getEntityManager();
        $evm = $entityManager->getEventManager();

        // Register subscribers
        $evm->addEventSubscriber(new DoctrineSubscriber($this->transactionManager));

        $this->loadAnnotations($entityManager);

        return $this;
    }

    /**
     * Returns true if $entity is auditable.
     *
     * @param object|string $entity
     */
    public function isAuditable($entity): bool
    {
        $class = DoctrineHelper::getRealClassName($entity);
        // is $entity part of audited entities?
        \assert($this->configuration instanceof Configuration);   // helps PHPStan
        if (!\array_key_exists($class, $this->configuration->getEntities())) {
            // no => $entity is not audited
            return false;
        }

        return true;
    }

    /**
     * Returns true if $entity is audited.
     *
     * @param object|string $entity
     */
    public function isAudited($entity): bool
    {
        if (!$this->auditor->getConfiguration()->isEnabled()) {
            return false;
        }

        /** @var Configuration $configuration */
        $configuration = $this->configuration;
        $class = DoctrineHelper::getRealClassName($entity);

        // is $entity part of audited entities?
        if (!\array_key_exists($class, $configuration->getEntities())) {
            // no => $entity is not audited
            return false;
        }

        $entityOptions = $configuration->getEntities()[$class];

        if (null === $entityOptions) {
            // no option defined => $entity is audited
            return true;
        }

        if (isset($entityOptions['enabled'])) {
            return (bool) $entityOptions['enabled'];
        }

        return true;
    }

    /**
     * Returns true if $field is audited.
     *
     * @param object|string $entity
     */
    public function isAuditedField($entity, string $field): bool
    {
        // is $field is part of globally ignored columns?
        \assert($this->configuration instanceof Configuration);   // helps PHPStan
        if (\in_array($field, $this->configuration->getIgnoredColumns(), true)) {
            // yes => $field is not audited
            return false;
        }

        // is $entity audited?
        if (!$this->isAudited($entity)) {
            // no => $field is not audited
            return false;
        }

        $class = DoctrineHelper::getRealClassName($entity);
        $entityOptions = $this->configuration->getEntities()[$class];

        if (null === $entityOptions) {
            // no option defined => $field is audited
            return true;
        }

        // are columns excluded and is field part of them?
        if (isset($entityOptions['ignored_columns']) &&
            \in_array($field, $entityOptions['ignored_columns'], true)) {
            // yes => $field is not audited
            return false;
        }

        return true;
    }

//    public function supportsStorage(): bool
//    {
//        return true;
//    }

    public function supportsAuditing(): bool
    {
        return true;
    }

    public function setStorageMapper(callable $storageMapper): void
    {
        \assert($this->configuration instanceof Configuration);   // helps PHPStan
        $this->configuration->setStorageMapper($storageMapper);
    }

    private function loadAnnotations(EntityManagerInterface $entityManager): self
    {
        \assert($this->configuration instanceof Configuration);   // helps PHPStan
        $annotationLoader = new AnnotationLoader($entityManager);
        $this->configuration->setEntities(array_merge(
            $this->configuration->getEntities(),
            $annotationLoader->load()
        ));

        return $this;
    }

    private function checkStorageMapper(): self
    {
        \assert($this->configuration instanceof Configuration);   // helps PHPStan
        if (null === $this->configuration->getStorageMapper() && $this->isStorageMapperRequired()) {
            throw new ProviderException('You must provide a mapper function to map audits to storage.');
        }

//        if (null === $this->getStorageMapper() && 1 === count($this->getStorageServices())) {
//            // No mapper and only 1 storage entity manager
//            return array_values($this->storageServices)[0];
//        }

        return $this;
    }

    public function persist(LifecycleEvent $event): void
    {
        // TODO: Implement persist() method.
    }
}
