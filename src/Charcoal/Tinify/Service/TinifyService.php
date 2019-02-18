<?php

namespace Charcoal\Tinify\Service;

//Psr
use RuntimeException;

// local dependencies
use Charcoal\Tinify\TinifyConfig;

// from 'tinify\tinify'
use Tinify\AccountException;

// I know, that's ugly.
use Tinify;

/**
 * Tinify Service
 */
class TinifyService
{
    /**
     * @var string $key
     */
    private $key;

    /**
     * The tinify config container.
     *
     * @var TinifyConfig $tinifyConfig
     */
    private $tinifyConfig;

    /**
     * Flag to keep track if the connection as been validated at least once.
     *
     * @var boolean $connectionValidated
     */
    protected $connectionValidated = false;

    /**
     * @param array $data The initial data.
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(array $data = [])
    {
        $this->setKey($data['key']);
        Tinify\setKey($this->key());

        $this->setTinifyConfig($data['tinify/config']);
    }

    /**
     * @throws RuntimeException When the connection with tinify is unsuccessful.
     * @return string|null
     */
    public function compressionCount()
    {
        if (!$this->validateConnection()) {
            throw new RuntimeException('Could not validate connection with Tinify Api.');
        }

        return Tinify\compressionCount();
    }

    /**
     * @return boolean
     * @throws AccountException When the connection as failed.
     */
    public function validateConnection()
    {
        if (!$this->connectionValidated) {
            $this->connectionValidated = \Tinify\validate();
        }

        return $this->connectionValidated;
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @param string $key Key for TinifyService.
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    // DEPENDENCIES
    // ==========================================================================

    /**
     * @throws RuntimeException If the model factory is missing.
     * @return TinifyConfig
     */
    public function tinifyConfig()
    {
        if (!isset($this->tinifyConfig)) {
            throw new RuntimeException(sprintf(
                'Tinify Config is not defined for [%s]',
                get_class($this)
            ));
        }

        return $this->tinifyConfig;
    }

    /**
     * @param TinifyConfig $tinifyConfig TinifyConfig for TinifyService.
     * @return self
     */
    public function setTinifyConfig(TinifyConfig $tinifyConfig)
    {
        $this->tinifyConfig = $tinifyConfig;

        return $this;
    }
}
