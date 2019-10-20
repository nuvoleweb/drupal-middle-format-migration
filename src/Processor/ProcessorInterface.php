<?php


namespace NuvoleWeb\DrupalMigration\Processor;

/**
 * Define processor interface.
 */
interface ProcessorInterface
{
    /**
     * Process JSON API attributes.
     *
     * @param array $attributes
     *    List or existing attributes, the processor will add its fields here.
     * @param \stdClass $entity
     *    Source entity.
     * @param string $language
     *    Language code to be processed.
     * @param array $configuration
     *    Current processor configuration.
     */
    public function processAttributes(array &$attributes, \stdClass $entity, $language, array $configuration);

    /**
     * Process JSON API metadata.
     *
     * @param array $metadata
     *    Existing metadata, the processor will add its fields here.
     * @param \stdClass $entity
     *    Source entity.
     * @param string $language
     *    Language code to be processed.
     * @param array $configuration
     *    Current processor configuration.
     */
    public function processMetadata(array &$metadata, \stdClass $entity, $language, array $configuration);
}
