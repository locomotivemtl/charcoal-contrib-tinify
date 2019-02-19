<?php

namespace Charcoal\Tinify\Contract\Object;

/**
 * Interface RegistryInterface
 * @package Muraflex\Contract\Object
 */
interface RegistryInterface
{
    /**
     * @param integer $size Size for Registry.
     * @return self
     */
    public function setSize($size);

    /**
     * @return integer
     */
    public function originalSize();

    /**
     * @param integer $originalSize OriginalSize for Registry.
     * @return self
     */
    public function setOriginalSize($originalSize);

    /**
     * @return integer
     */
    public function memorySaved();

    /**
     * @param integer $memorySaved MemorySaved for Registry.
     * @return self
     */
    public function setMemorySaved($memorySaved);

    /**
     * @return string
     */
    public function basename();

    /**
     * @param string $basename Basename for Registry.
     * @return self
     */
    public function setBasename($basename);

    /**
     * @return string
     */
    public function filename();

    /**
     * @param string $filename Filename for Registry.
     * @return self
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function extension();

    /**
     * @param string $extension Extension for Registry.
     * @return self
     */
    public function setExtension($extension);
}
