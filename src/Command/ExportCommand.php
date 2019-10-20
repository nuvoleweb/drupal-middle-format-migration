<?php

namespace NuvoleWeb\DrupalMigration\Command;

use \NuvoleWeb\DrupalMigration\Drupal\Driver;
use League\Fractal\Manager;
use League\Fractal\Resource;
use NuvoleWeb\DrupalMigration\ProcessorManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use NuvoleWeb\DrupalMigration\ExportWriter;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExportCommand extends Command
{
    /**
     * @var \League\Fractal\Manager
     */
    protected $manager;

    /**
     * @var \NuvoleWeb\DrupalMigration\Drupal\Driver
     */
    protected $driver;

    /**
     * @var \NuvoleWeb\DrupalMigration\ExportWriter
     */
    protected $exportWriter;

    /**
     * @var \NuvoleWeb\DrupalMigration\ProcessorManager
     */
    protected $processorManager;

    /**
     * ExportCommand constructor.
     *
     * @param \League\Fractal\Manager $manager
     * @param \NuvoleWeb\DrupalMigration\Drupal\Driver $driver
     * @param \NuvoleWeb\DrupalMigration\ExportWriter $exportWriter
     * @param \NuvoleWeb\DrupalMigration\ProcessorManager $processorManager
     */
    public function __construct(
        Manager $manager,
        Driver $driver,
        ExportWriter $exportWriter,
        ProcessorManager $processorManager
    ) {
        $this->manager = $manager;
        $this->driver = $driver;
        $this->exportWriter = $exportWriter;
        $this->processorManager = $processorManager;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('export')
            ->addArgument('type', InputArgument::REQUIRED, 'Entity type, e.g. "node".')
            ->addArgument('bundle', InputArgument::REQUIRED, 'Entity bundle, e.g. "article".')
            ->setDescription('Export entities of given bundle.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $entityType = $input->getArgument('type');
        $bundle = $input->getArgument('bundle');

        // Bootstrap Drupal.
        $this->driver->bootstrap();

        // Clear previous export, if any.
        $this->exportWriter->clear($entityType, $bundle);

        // Loop on entities and export them.
        $entities = $this->driver->loadEntities($entityType, $bundle);

        // Start progress bar.
        $total = count($entities);
        $io->progressStart($total);

        foreach ($entities as $entity) {
            // @todo: add support for multilingualism.
            $language = 'und';

            // Create a new JSON API resource.
            $resource = new Resource\Item($entity, function ($entity) use ($entityType, $bundle, $language) {
                $attributes = $this->getDefaultAttributes($entity, $entityType, $bundle);
                $this->processorManager->processAttributes($attributes, $entity, $entityType, $bundle, $language);
                return $attributes;
            }, $bundle);

            // Add metadata to JSON API resource.
            $metadata = [];
            $this->processorManager->processMetadata($metadata, $entity, $entityType, $bundle, $language);
            $resource->setMeta($metadata);

            // Serialize resource in JSON format.
            $content = $this->manager->createData($resource)->toJson(JSON_PRETTY_PRINT);

            // Write resource on the filesystem.
            $this->exportWriter->write($entityType, $bundle, $language, $entity->nid, $content);

            $io->progressAdvance();
        }

        $io->progressFinish();

        $path = $this->exportWriter->getContentPath($entityType, $bundle);
        $io->success("{$total} entities exported to " . realpath($path));
    }

    /**
     * Get default attributes.
     *
     * @param \stdClass $entity
     * @param $entityType
     * @param $bundle
     *
     * @return array
     */
    protected function getDefaultAttributes(\stdClass $entity, $entityType, $bundle)
    {
        $id = $this->driver->getEntityId($entityType, $entity);
        return [
            'id' => $id,
            'links' => [
                'self' => $bundle . '/' . $id
            ]
        ];
    }
}
