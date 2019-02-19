<?php

namespace Charcoal\Tinify\Service;

//Psr
use Charcoal\Admin\Ui\FeedbackContainerTrait;
use RuntimeException;

// from charcoal-core
use Charcoal\Model\ModelFactoryTrait;
use Charcoal\Model\ModelInterface;

// from charcoal-translator
use Charcoal\Translator\TranslatorAwareTrait;

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
    use ModelFactoryTrait;
    use TranslatorAwareTrait;
    use FeedbackContainerTrait;

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
        // Satisfies local dependencies
        $this->setKey($data['key']);
        Tinify\setKey($this->key());

        $this->setTinifyConfig($data['tinify/config']);

        // Satisfies ModelFactoryTrait
        $this->setModelFactory($data['model/factory']);

        // Satisfies TranslatorAwareTrait
        $this->setTranslator($data['translator']);

        // create dependable tables
        $this->createObjTable($this->registryProto());
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

    public function numCompressedFiles()
    {
        //TODO compare files against database.
    }

    /**
     * @return mixed
     */
    public function numFiles()
    {
        if (!isset($this->numFiles)) {
            $this->numFiles = count($this->filesData());
        }

        return $this->numFiles;
    }

    /**
     * @return mixed
     */
    public function totalSize()
    {
        if (!isset($this->totalSize)) {
            $this->totalSize = array_sum(array_column($this->filesData(), 'size'));
        }

        return $this->totalSize;
    }

    /**
     * Get images from basePath and store their data in an array.
     *
     * @return array
     */
    private function filesData()
    {
        if (isset($this->filesData)) {
            return $this->filesData;
        }

        $basePath   = $this->tinifyConfig()->basePath();
        $extensions = implode(',', $this->tinifyConfig()->fileExtensions());

        $files = $this->globRecursive(
            sprintf('%s/*.{%s}', $basePath, $extensions),
            GLOB_BRACE
        );

        $filesData = [];

        foreach ($files as $file) {
            $filesData[] = array_merge([
                'id'   => md5_file($file),
                'size' => filesize($file)
            ], pathinfo($file));
        }

        $this->filesData = $filesData;

        return $this->filesData;
    }

    /**
     * Recursively find pathnames matching a pattern
     *                         an empty array if no file matched or FALSE on error.
     * @see glob() for a description of the function and its parameters.
     *
     * @param  string  $pattern The search pattern.
     * @param  integer $flags   The glob flags.
     * @return array   Returns an array containing the matched files/directories,
     */
    private function globRecursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', (GLOB_ONLYDIR | GLOB_NOSORT)) as $dir) {
            $files = array_merge($files, $this->globRecursive($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }

    // GETTERS AND SETTERS
    // ==========================================================================

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

    /**
     * @return ModelInterface|mixed
     */
    protected function registryProto()
    {
        if (isset($this->registryProto)) {
            return $this->registryProto;
        }

        $this->registryProto = $this->modelFactory()->create($this->tinifyConfig()->registryObject());

        return $this->registryProto;
    }

    // Utils
    // ==========================================================================

    /**
     * @param ModelInterface $proto Prototype to ensure table creation for.
     * @return void
     */
    private function createObjTable(ModelInterface $proto)
    {
        $obj = $proto;
        if (!$obj) {
            return;
        }

        if ($obj->source()->tableExists() === false) {
            $obj->source()->createTable();
            $msg = $this->translator()->translate('Database table created for "{{ objType }}".', [
                '{{ objType }}' => $obj->objType()
            ]);
            $this->addFeedback(
                'notice',
                '<span class="fa fa-asterisk" aria-hidden="true"></span><span>&nbsp; '.$msg.'</span>'
            );
        }
    }
}
