<?php


namespace NuvoleWeb\DrupalMigration\Processor;

use \NuvoleWeb\DrupalMigration\Drupal\Driver;

abstract class BaseProcessor implements ProcessorInterface
{
    /**
     * @var \NuvoleWeb\DrupalMigration\Drupal\Driver
     */
    protected $driver;

    /**
     * BaseProcessor constructor.
     *
     * @param \NuvoleWeb\DrupalMigration\Drupal\Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * {@inheritdoc}
     */
    public function processAttributes(array &$attributes, \stdClass $entity, $language, array $configuration)
    {
        // Empty method, to be optionally implemented by child class.
    }

    /**
     * {@inheritdoc}
     */
    public function processMetadata(array &$metadata, \stdClass $entity, $language, array $configuration)
    {
        // Empty method, to be optionally implemented by child class.
    }

    /**
     * Get field value from entity or its provided default if none set.
     *
     * @param \stdClass $entity
     * @param string $field
     * @param string $language
     * @param mixed $default
     *
     * @return mixed|null
     */
    protected function getFieldValues(\stdClass $entity, $field, $language, $default = [])
    {
        return isset($entity->{$field}[$language]) ? $entity->{$field}[$language] : $default;
    }

    /**
     * Set property value.
     *
     * @param array $attributed
     * @param string $name
     * @param string $value
     */
    protected function setAttributeValue(array &$attributed, $name, $value)
    {
        if (!isset($attributed[$name])) {
            $attributed[$name] = [];
        }

        $attributed[$name][] = $value;
    }
}
