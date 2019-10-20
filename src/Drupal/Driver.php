<?php


namespace NuvoleWeb\DrupalMigration\Drupal;

use Drupal\Driver\DrupalDriver;

/**
 * Extend Drupal driver.
 */
class Driver extends DrupalDriver
{
    /**
     * Load Drupal entities.
     *
     * @param string $entityType
     *   Entity type to be loaded.
     * @param string $bundle
     *   Entity bundle.
     * @param int|null $start
     *   The first entity from the result set to return. If NULL, removes any
     *   range directives that are set.
     * @param int|null $length
     *   The number of entities to return from the result set.
     *
     * @return array
     */
    public function loadEntities($entityType, $bundle, $start = null, $length = null)
    {
        // Instantiate Drupal 7 EntityFieldQuery.
        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', $entityType)
            ->entityCondition('bundle', $bundle);

        // Add query range, useful for pagination in calling service.
        if ($start !== null && $length !== null) {
            $query->range($start, $length);
        }

        // Make sure we run queries as user 1 so we avoid permissions problems.
        $query->addMetaData('account', user_load(1));

        // Run query.
        $result = $query->execute();
        if (isset($result[$entityType])) {
            $ids = array_keys($result[$entityType]);
            return entity_load($entityType, $ids);
        }

        return [];
    }

    /**
     * Get value of the current entity ID.
     *
     * @param string $entityType
     * @param object $entity
     *
     * @return int
     */
    public function getEntityId($entityType, $entity)
    {
        $info = entity_get_info($entityType);
        return $entity->{$info['entity keys']['id']};
    }
}
