<?php

namespace Charcoal\Tinify;

// from 'charcoal-config'
use Charcoal\Config\AbstractConfig;

/**
 * Tinify Contrib Module Config
 */
class TinifyConfig extends AbstractConfig
{

    /**
     * @var integer $maxCompressions
     */
    private $maxCompressions;

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
}
