<?php

namespace NuvoleWeb\DrupalMigration\Processor;

/**
 * Process long text fields with summary.
 */
class LongTextAndSummaryProcessor extends BaseProcessor
{
    /**
     * {@inheritdoc}
     */
    public function processAttributes(array &$attributes, \stdClass $entity, $language, array $configuration)
    {
        $destination = $configuration['destination'];
        $values = $this->getFieldValues($entity, $configuration['source'], $language, []);
        foreach ($values as $key => $value) {
            $this->setAttributeValue($attributes, "{$destination}_value", $value['safe_value']);
            $this->setAttributeValue($attributes, "{$destination}_summary", $value['safe_summary']);
        }
    }
}
