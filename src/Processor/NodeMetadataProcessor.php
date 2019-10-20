<?php


namespace NuvoleWeb\DrupalMigration\Processor;

/**
 * Provide base node metadata
 */
class NodeMetadataProcessor extends BaseProcessor
{
    /**
     * {@inheritdoc}
     */
    public function processMetadata(array &$metadata, \stdClass $entity, $language, array $configuration)
    {
        $metadata['language'] = $language;
        $metadata['revision_id'] = $entity->vid;
        $metadata['status'] = $entity->status;
        $metadata['promote'] = $entity->promote;
        $metadata['sticky'] = $entity->sticky;
        $metadata['created'] = $entity->created;
        $metadata['changed'] = $entity->changed;
    }
}
