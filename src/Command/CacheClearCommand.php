<?php

namespace NuvoleWeb\DrupalMigration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CacheClearCommand extends Command
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fs;

    /**
     * CacheClearCommand constructor.
     *
     * @param \Symfony\Component\Filesystem\Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clear application cache.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        if ($fs->exists(__DIR__ . '/../../var/cache')) {
            $fs->remove(__DIR__ . '/../../var/cache');
        }
        $output->writeln('Cache cleared.');
    }
}
