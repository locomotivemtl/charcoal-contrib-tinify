<?php

namespace Charcoal\Tinify\Service;

//Psr
use Charcoal\Admin\Ui\FeedbackContainerTrait;
use Psr\Log\LoggerAwareTrait;
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

// from pdo
use PDO;

/**
 * Tinify Service
 */
class TinifyService
{
    use ModelFactoryTrait;
    use TranslatorAwareTrait;
    use FeedbackContainerTrait;
    use LoggerAwareTrait;

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
    private $connectionValidated = false;

    /**
     * Flag to keep track of the parsing of the files data against the registry.
     *
     * @var boolean $dataParsed
     */
    private $dataParsed = false;

    /**
     * Container for compressed files.
     *
     * @var array $compressedFiles
     */
    private $compressedFiles = [];

    /**
     * Container for uncompressed files.
     *
     * @var array $uncompressedFiles
     */
    private $uncompressedFiles = [];

    /**
     * @var PDO $dbh
     */
    private $dbh;

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

        $this->dbh = $data['database'];

        // create dependable tables
        $this->createObjTable($this->registryProto());

        $this->setLogger($data['logger']);
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
     * @return \Generator
     */
    public function compressFiles()
    {
        foreach ($this->uncompressedFiles() as $file) {
            if (file_exists($file['file'])) {
                $source = Tinify\fromFile($file['file']);
                $source->toFile($file['file']);

                $file['id']            = md5_file($file['file']);
                $file['original_size'] = $file['size'];
                $file['size']          = filesize($file['file']);

                $registry = $this->modelFactory()
                                 ->create($this->tinifyConfig()->registryObject())
                                 ->setData($file);

                //@TODO Register the compression in a log of some sort.

                try {
                    $registry->save();

                    yield $registry;
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function numCompressedFiles()
    {
        if (!isset($this->numCompressedFiles)) {
            $this->numCompressedFiles = count($this->compressedFiles());
        }

        return $this->numCompressedFiles;
    }

    /**
     * @return mixed
     */
    public function numUncompressedFiles()
    {
        if (!isset($this->numUncompressedFiles)) {
            $this->numUncompressedFiles = count($this->uncompressedFiles());
        }

        return $this->numUncompressedFiles;
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
     * @return mixed
     */
    public function totalMemorySaved()
    {
        if (!isset($this->totalMemorySaved)) {
            // Ensure the file containers are parsed
            $this->parseFilesData();

            $this->totalMemorySaved = array_sum(array_column($this->compressedFiles, 'memory_saved'));
        }

        return $this->totalMemorySaved;
    }

    /**
     * @return float
     */
    public function compressionPercentage()
    {
        if (!isset($this->compressionPercentage)) {
            // Ensure the file containers are parsed
            $this->parseFilesData();

            $this->compressionPercentage = floor(
                $this->totalMemorySaved() * 100 / (
                    array_sum(array_column($this->compressedFiles, 'original_size')) +
                    array_sum(array_column($this->uncompressedFiles, 'size'))
                )
            );
        }

        return $this->compressionPercentage;
    }

    /**
     * Fetch the registries from the registries table
     * and return the data as a dictionary.
     *
     * @return mixed
     */
    private function fileRegistries()
    {
        if (isset($this->fileRegistries)) {
            return $this->fileRegistries;
        }

        $q = 'SELECT id, size, original_size, memory_saved FROM `%table%`
              WHERE active = 1';

        $q   = strtr($q, ['%table%' => $this->registryProto()->source()->table()]);
        $sth = $this->dbh->prepare($q);
        $sth->execute();

        $registries = [];
        $sth->fetchAll(PDO::FETCH_FUNC, function ($id, $size, $originalSize, $memorySaved) use (&$registries) {
            $registries[$id] = [
                'size'          => $size,
                'original_size' => $originalSize,
                'memory_saved'  => $memorySaved,
            ];
        });

        $this->fileRegistries = $registries;

        return $this->fileRegistries;
    }

    /**
     * Prepare the file containers.
     *
     * @return void
     */
    private function parseFilesData()
    {
        if (!!$this->dataParsed) {
            return;
        }

        $this->dataParsed = true;

        $filesData = $this->filesData();
        array_walk($filesData, function ($data) {
            if (isset($this->fileRegistries()[$data['id']])) {
                $this->compressedFiles[] =
                    array_replace($data, $this->fileRegistries()[$data['id']]);
            } else {
                $this->uncompressedFiles[] = $data;
            }
        });
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
                'file' => $file,
                'size' => filesize($file)
            ], pathinfo($file));
        }

        $this->filesData = $filesData;

        return $this->filesData;
    }

    /**
     * @return array
     */
    public function uncompressedFiles()
    {
        // Ensure the file containers are parsed
        $this->parseFilesData();
        return $this->uncompressedFiles;
    }

    /**
     * @return array
     */
    public function compressedFiles()
    {
        // Ensure the file containers are parsed
        $this->parseFilesData();
        return $this->compressedFiles;
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
