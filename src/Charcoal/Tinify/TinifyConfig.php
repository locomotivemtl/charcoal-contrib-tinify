<?php

namespace Charcoal\Tinify;

// from 'charcoal-config'
use Charcoal\Config\AbstractConfig;
use RuntimeException;

/**
 * Tinify Contrib Module Config
 */
class TinifyConfig extends AbstractConfig
{

    /**
     * The max amount of compressions per month.
     *
     * @var integer $maxCompressions
     */
    private $maxCompressions;

    /**
     * The path in which to find the files to optimize,
     *
     * @var string $basePath
     */
    private $basePath;

    /**
     * Supported file extensions
     *
     * @var array $fileExtensions
     */
    private $fileExtensions;

    /**
     * The default data is defined in a JSON file.
     *
     * @return array
     */
    public function defaults()
    {
        $baseDir = rtrim(realpath(__DIR__.'/../../../'), '/');
        $confDir = $baseDir.'/config';

        $tinifyConfig = $this->loadFile($confDir.'/tinify.json');

        return $tinifyConfig;
    }

    /**
     * @return integer
     */
    public function maxCompressions()
    {
        return $this->maxCompressions;
    }

    /**
     * @param integer $maxCompressions MaxCompressions for TinifyConfig.
     * @return self
     */
    public function setMaxCompressions($maxCompressions)
    {
        $this->maxCompressions = $maxCompressions;

        return $this;
    }

    /**
     * @throws RuntimeException If basePath is missing or not a string.
     * @return string
     */
    public function basePath()
    {
        if (!isset($this->basePath) || !is_string($this->basePath)) {
            throw new RuntimeException(sprintf(
                'Base Path is not set or not of type (string) in [%s]',
                get_class($this)
            ));
        }

        return $this->basePath;
    }

    /**
     * @param string $basePath BasePath for TinifyConfig.
     * @return self
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @throws RuntimeException When the file extensions is not set or empty.
     * @return array
     */
    public function fileExtensions()
    {
        if (!isset($this->fileExtensions) || empty($this->fileExtensions)) {
            throw new RuntimeException(sprintf(
                'File Extensions is not set or empty in [%s]',
                get_class($this)
            ));
        }

        return $this->fileExtensions;
    }

    /**
     * @param array $fileExtensions FileExtensions for TinifyConfig.
     * @return self
     */
    public function setFileExtensions(array $fileExtensions)
    {
        $this->fileExtensions = $fileExtensions;

        return $this;
    }
}
