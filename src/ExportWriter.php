<?php

namespace NuvoleWeb\DrupalMigration;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Write exported JSON files to disk.
 */
class ExportWriter
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $exportRoot;

    /**
     * ExportWriter constructor.
     *
     * @param $exportRoot
     * @param \Symfony\Component\Filesystem\Filesystem $fs
     */
    public function __construct($exportRoot, Filesystem $fs)
    {
        $this->exportRoot = $exportRoot;
        $this->fs = $fs;
    }

    /**
     * Write export file and return its path.
     *
     * @param $entityType
     * @param $bundle
     * @param $language
     * @param $id
     * @param $content
     *
     * @return string
     */
    public function write($entityType, $bundle, $language, $id, $content)
    {
        $basePath = $this->ensureDirectory($entityType, $bundle, $language);
        $filename = "$basePath/$id.json";
        file_put_contents($filename, $content);

        return $filename;
    }

    /**
     * Delete all content of given directory.
     *
     * @param $entityType
     * @param $bundle
     */
    public function clear($entityType, $bundle)
    {
        $this->fs->remove($this->getContentPath($entityType, $bundle));
    }

    /**
     * Get content path root.
     *
     * @param $entityType
     * @param $bundle
     *
     * @return string
     */
    public function getContentPath($entityType, $bundle)
    {
        // Clear directory and recreated it empty.
        $root = $this->ensureRoot();
        return "$root/$entityType/$bundle";
    }

    /**
     * Ensure that export destination directory exists.
     *
     * @param $entityType
     * @param $bundle
     * @param $language
     *
     * @return string
     */
    protected function ensureDirectory($entityType, $bundle, $language)
    {
        $directory = $this->getContentPath($entityType, $bundle) . '/' . $language;
        $this->fs->mkdir($directory);

        return $directory;
    }

    /**
     * If path is relative make sure that it refers to current repo's root.
     *
     * This is necessary as booting Drupal will change the current working directory.
     *
     * @return string
     */
    protected function ensureRoot()
    {
        $root = $this->exportRoot;
        if (!$this->fs->isAbsolutePath($root)) {
            $root = __DIR__ . '/../' . $root;
        }

        return $root;
    }
}
