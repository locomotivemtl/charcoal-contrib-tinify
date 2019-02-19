<?php

namespace Charcoal\Tinify\Object;

// From Charcoal-Object
use Charcoal\Object\Content;

// From muraflex
use Charcoal\Tinify\Contract\Object\RegistryInterface;

/**
 * Registry Object
 */
class Registry extends Content implements
    RegistryInterface
{
    /**
     * @var integer $size
     */
    private $size;

    /**
     * @var integer $originalSize
     */
    private $originalSize;

    /**
     * @var integer $memorySaved
     */
    private $memorySaved;

    /**
     * @var string $basename
     */
    private $basename;

    /**
     * @var string $filename
     */
    private $filename;

    /**
     * @var string $extension
     */
    private $extension;

    /**
     * @return integer
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * @param integer $size Size for Registry.
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return integer
     */
    public function originalSize()
    {
        return $this->originalSize;
    }

    /**
     * @param integer $originalSize OriginalSize for Registry.
     * @return self
     */
    public function setOriginalSize($originalSize)
    {
        $this->originalSize = $originalSize;

        return $this;
    }

    /**
     * @return integer
     */
    public function memorySaved()
    {
        return $this->memorySaved;
    }

    /**
     * @param integer $memorySaved MemorySaved for Registry.
     * @return self
     */
    public function setMemorySaved($memorySaved)
    {
        $this->memorySaved = $memorySaved;

        return $this;
    }

    /**
     * @return string
     */
    public function basename()
    {
        return $this->basename;
    }

    /**
     * @param string $basename Basename for Registry.
     * @return self
     */
    public function setBasename($basename)
    {
        $this->basename = $basename;

        return $this;
    }

    /**
     * @return string
     */
    public function filename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename Filename for Registry.
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function extension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension Extension for Registry.
     * @return self
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * StorableTrait > preSave(): Called automatically before saving the object to source.
     * For content object, set the `created` and `lastModified` properties automatically
     * @return boolean
     */
    protected function preSave()
    {
        // Calculate the amount of memory saved.
        $this->setMemorySaved($this->originalSize() - $this->size());

        return parent::preSave();
    }
}
