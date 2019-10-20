<?php


namespace NuvoleWeb\DrupalMigration;

use NuvoleWeb\DrupalMigration\Processor\ProcessorInterface;

class ProcessorManager
{
    /**
     * Export configuration.
     *
     * @var array
     */
    protected $configuration;

    /**
     * @var \NuvoleWeb\DrupalMigration\Processor\ProcessorInterface[]
     */
    protected $processors;

    /**
     * ProcessorManager constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Add available processor managers.
     *
     * @param \NuvoleWeb\DrupalMigration\Processor\ProcessorInterface $processor
     *
     * @throws \ReflectionException
     */
    public function add(ProcessorInterface $processor)
    {
        $reflection = new \ReflectionClass($processor);
        $name = $reflection->getShortName();
        $this->processors[$name] = $processor;
    }

    /**
     * Process attributes.
     *
     * @param array $attributes
     * @param $entity
     * @param $entityType
     * @param $bundle
     * @param $language
     */
    public function processAttributes(array &$attributes, $entity, $entityType, $bundle, $language)
    {
        foreach ($this->getConfiguration($entityType, $bundle) as $key => $configuration) {
            $this->getProcessor($configuration['processor'], $key, $entityType, $bundle)
                ->processAttributes($attributes, $entity, $language, $configuration);
        }
        ksort($attributes);
    }

    /**
     * Process metadata.
     *
     * @param array $metadata
     * @param $entity
     * @param $entityType
     * @param $bundle
     * @param $language
     */
    public function processMetadata(array &$metadata, $entity, $entityType, $bundle, $language)
    {
        foreach ($this->getConfiguration($entityType, $bundle) as $key => $configuration) {
            $this->getProcessor($configuration['processor'], $key, $entityType, $bundle)
                ->processMetadata($metadata, $entity, $language, $configuration);
        }
        ksort($metadata);
    }

    /**
     * Get configuration.
     *
     * @param string $entityType
     * @param string $bundle
     *
     * @return array
     */
    protected function getConfiguration($entityType, $bundle)
    {
        if (!isset($this->configuration[$entityType][$bundle])) {
            throw new \RuntimeException("No export configuration found for {$entityType} of type {$bundle}.");
        }

        return $this->configuration[$entityType][$bundle];
    }

    /**
     * Get processor instance.
     *
     * @param string $processor
     * @param int $key
     * @param string $entityType
     * @param string $bundle
     *
     * @return \NuvoleWeb\DrupalMigration\Processor\ProcessorInterface
     */
    protected function getProcessor($processor, $key, $entityType, $bundle)
    {
        if (!isset($this->processors[$processor])) {
            $message = "Processor {$processor} not found on item {$key} for {$entityType} of type {$bundle}.";
            throw new \RuntimeException($message);
        }

        return $this->processors[$processor];
    }
}
