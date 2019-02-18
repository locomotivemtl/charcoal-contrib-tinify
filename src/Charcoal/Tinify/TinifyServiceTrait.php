<?php

namespace Charcoal\Tinify;

use Charcoal\Tinify\Service\TinifyService;
use RuntimeException;

/**
 * Provides tinify service features.
 *
 * Trait TinifyServiceTrait
 * @package Charcoal\Tinify
 */
trait TinifyServiceTrait
{
    /**
     * @var TinifyService $tinifyService
     */
    private $tinifyService;

    /**
     * @throws RuntimeException If the Tinify service is missing.
     * @return TinifyService
     */
    public function tinifyService()
    {
        if (!isset($this->tinifyService)) {
            throw new RuntimeException(sprintf(
                'Tinify Service is not defined for [%s]',
                get_class($this)
            ));
        }

        return $this->tinifyService;
    }

    /**
     * @param TinifyService $tinifyService TinifyService for ImageCompressionTemplate.
     * @return self
     */
    public function setTinifyService(TinifyService $tinifyService)
    {
        $this->tinifyService = $tinifyService;

        return $this;
    }
}
