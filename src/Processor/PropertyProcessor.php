<?php

namespace NuvoleWeb\DrupalMigration\Processor;

/**
 * Process simple entity properties, such as title, status, etc.
 */
class PropertyProcessor extends BaseProcessor
{
    /**
     * {@inheritdoc}
     */
    public function processAttributes(array &$attributes, \stdClass $entity, $language, array $configuration)
    {
        $destination = $configuration['destination'];
        $this->setAttributeValue($attributes, $destination, $entity->{$configuration['source']});
    }
}
